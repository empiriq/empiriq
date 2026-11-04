# Market data

> Status: **draft**. Reference for authors of feeders and backfillers. It
> describes what goes into `market.*`, how to write it, and which values the
> database derives.

A feeder writes the live market data that live requests ask for. A
backfiller loads requested historical market data. Both use the same
tables and the same rules on this page. The sections
[Writers and seq](#writers-and-seq), [Heartbeat](#heartbeat) and
[Backfill](#backfill) say what differs.

Feeders and backfillers send two event types: **trades** and **depth
snapshots**. Each event becomes one row. The database derives all other
values: bars, best bid and best ask, notional. So all strategies see the same values.

## Time

Every event has timestamps from the venue in Unix milliseconds. The database
stores them as `timestamptz`. `timestamptz` has a precision of one
microsecond, so milliseconds fit exactly. In Go, use `time.UnixMilli(ms)`.

| Column        | Meaning                                                       | Use |
|---------------|---------------------------------------------------------------|-----|
| `event_time`  | When the venue **published** the event                        | Visibility: a frame at `as_of` sees an event only if `event_time <= as_of` |
| `trade_time`  | When the trade **happened**. Trades only, `<= event_time`.    | Puts trades into periods for bars |
| `received_at` | When the row arrived in the database. The default sets it.    | Latency monitoring of the feed |

Visibility uses the publish time. This keeps backtests reliable. Example: a
trade happened at 00:09:59.900 UTC, and the venue published it at
00:10:00.500 UTC. The trade belongs to the 00:09 bar. But a frame with
`as_of` = 00:10:00 UTC does not see it.

## Instruments

Events identify an instrument by the venue symbol, for example `BTCUSDT`.
Tables store `instrument_id`. A feeder gets the id for each symbol once, at
start. Then it keeps the id in a cache:

```sql
select api.instrument_id('binance', 'BTCUSDT');
```

A row with an unknown instrument fails on the foreign key.

Feeders and backfillers report the instruments of their venues
([ADR 0018](../adr/0018-services-report-instruments.md)), at start and every
hour. A report is the complete list of one venue, as the venue publishes it:

```sql
select api.report_instruments('binance-futures-um', '[
  {"symbol": "BTCUSDT", "kind": "perpetual", "base_asset": "BTC",
   "quote_asset": "USDT", "settle_asset": "USDT", "active": true}
]');
```

- Missing venues, assets and instruments are created. Changed ones are
  updated. Ids never change.
- Instruments that are not in the list any more become inactive.
- An empty list is an error.

## Numbers

Prices and quantities are `numeric`, which stores exact decimals. Binary
floats cannot store most decimal prices exactly. For example, `0.1` has no
exact float value. The errors grow in sums and comparisons.

- If the client library gives the original decimal strings of the venue, use
  them.
- If you have only `float64`, format it with
  `strconv.FormatFloat(f, 'f', -1, 64)`. This gives the shortest decimal that
  converts back to the same float. If the float came from a decimal string of
  the venue, the result is that original string.

## Trades

Use **raw trades**: one row is one trade on the venue, so
`first_trade_id = last_trade_id`. Live and historical data then have the same
shape. The columns are a range, so an aggregated source stays possible later.

| Go (`Trade`)   | Column           | Type          | Rules |
|----------------|------------------|---------------|-------|
| `Symbol`       | `instrument_id`  | `bigint`      | Get it from `api.instrument_id` |
| `EventTime`    | `event_time`     | `timestamptz` | |
| `TradeTime`    | `trade_time`     | `timestamptz` | |
| `Price`        | `price`          | `numeric`     | `> 0` |
| `Quantity`     | `qty`            | `numeric`     | `> 0`. Base asset. Total of all source trades in the row. |
| `FirstTradeID` | `first_trade_id` | `bigint`      | Primary key, together with `instrument_id` |
| `LastTradeID`  | `last_trade_id`  | `bigint`      | `>= first_trade_id`. Equal for raw trades. |
| *proposed:* `IsBuyerMaker` | `aggressor` | `core.order_side` | `sell` if the buyer was the maker, else `buy`. Null if unknown. |
| —              | `writer_id`      | `bigint`      | From `api.register_writer()` or `api.start_backfill()` |
| —              | `seq`            | `bigint`      | Set by the database |
| —              | `received_at`    | `timestamptz` | Default `clock_timestamp()` |

**Idempotency.** The primary key is `(instrument_id, first_trade_id)`. So you
can write the same trade again without harm, with
`insert ... on conflict do nothing`. This covers a reconnect, a replay, a
second feeder, or a backfill that overlaps with live data. `COPY` cannot skip
conflicts. For bulk loads, use a staging table (see the example below).

The `Trade` event has no field for the aggressor side yet. Raw trades on most
venues have it (for example `isBuyerMaker`). With it, strategies can use
trade-flow features such as the imbalance of buys and sells.

## Depth snapshots

One row is one depth snapshot: the top N price levels of each book side at
`event_time`. A snapshot **replaces** the previous one completely.

- There are no incremental updates.
- Price levels after level N are unknown.
- A feeder that receives a stream of book changes keeps the book in memory.
  It writes snapshots.

The price levels are parallel arrays. Index 1 is the best level.

| Go (`Depth` / `DepthLevel`) | Column          | Type          | Rules |
|-----------------------------|-----------------|---------------|-------|
| `Symbol`                    | `instrument_id` | `bigint`      | Get it from `api.instrument_id` |
| —                           | `writer_id`     | `bigint`      | From `api.register_writer()` |
| `EventTime`                 | `event_time`    | `timestamptz` | Primary key, together with `instrument_id` |
| `Bids()` → `Price`          | `bid_price`     | `numeric[]`   | Strictly descending, `> 0` |
| `Bids()` → `Quantity`       | `bid_qty`       | `numeric[]`   | Same length as `bid_price`, `> 0` |
| `Asks()` → `Price`          | `ask_price`     | `numeric[]`   | Strictly ascending, `> 0` |
| `Asks()` → `Quantity`       | `ask_qty`       | `numeric[]`   | Same length as `ask_price`, `> 0` |
| `Notional`                  | —               |               | Not stored. Derived as `price * qty`. |
| —                           | `received_at`   | `timestamptz` | Default `clock_timestamp()` |

Check constraints reject a depth snapshot with any of these errors:

- arrays of different lengths;
- prices that are not sorted, or the same price twice;
- values that are zero or negative;
- a crossed book: the best bid is equal to or higher than the best ask.

An empty book side (`'{}'`) is allowed.

The database does not store `Notional`, because price and quantity define it
completely. A stored copy could disagree with them. `api.depth_levels()`
returns notional and running totals.

## Writers and seq

Every process that writes market data is a **writer**
([ADR 0009](../adr/0009-writers.md)). Every row has a `writer_id` and a `seq`
that the database assigns at insert.

- A feeder registers once at start (see [Live requests](#live-requests)).
  `api.register_feeder()` returns the id of its writer. Put it into every
  row. After a restart, the same name returns the same writer.
- A backfill run gets its own writer from `api.start_backfill()` (see
  [Backfill](#backfill)).
- **One rule:** a writer commits its transactions for one instrument one after
  the other. Parallel work on different instruments is allowed.

Frames record, for each open writer, its highest `seq` as a cutoff. A frame
sees no row of that writer above the cutoff. When a writer is finished, it
calls `api.close_writer(writer_id)`. Frames that start later see all its rows.
A closed writer cannot write again.

## Live requests

Live data is declarative ([ADR 0016](../adr/0016-live-requests.md)). An
operator adds a live request for each stream that feeders must write. A
feeder works like this:

1. At start, register. The name identifies the feeder. The result is the id
   of its writer:
   ```sql
   select api.register_feeder('binance-feeder',
     array['binance-spot', 'binance-futures-um'], array['trade']::market.stream[],
     'v1.0.0', '{"heartbeat_interval": "30 seconds"}');
   ```
2. `LISTEN empiriq_live`.
3. Get the streams to write. The call is also a heartbeat:
   ```sql
   select request_id, instrument_id, venue_id, symbol, stream from api.live_streams('binance-feeder');
   ```
4. Subscribe to new streams at the venue. Unsubscribe from streams that are
   not in the result any more.
5. Repeat step 3 after each notification, and at least every
   `heartbeat_interval`.

Every alive feeder with the capability writes a live request. If two feeders
write the same stream, the primary keys skip the duplicates.

## Heartbeat

For each stream, a feeder sends a heartbeat regularly, also when no events
arrive:

```sql
select api.report_watermark($writer_id, $instrument_id, 'trade', $complete_until);
-- After each connect or reconnect:
select api.report_watermark($writer_id, $instrument_id, 'trade', $complete_until,
                            p_since => $subscribed_at);
```

`complete_until` means: the database contains every event of this stream with
`event_time <= complete_until`. In a quiet market the heartbeat continues, so
strategies run on unchanged data. A stale heartbeat shows a broken feeder.

Each report also records *live coverage* ([ADR 0014](../adr/0014-live-coverage.md)):
the periods in which this writer stored every event. A report continues the
last period of the writer in two cases:

- `p_since` is at or before the end of that period;
- `p_since` is null, and `complete_until` jumped by at most `p_max_jump`
  (default 1 minute).

Otherwise a new period starts. So pass `p_since` after each connect and
reconnect: the time since which the feeder stored every event. If the
connection broke, the time between the old and the new period becomes an
uncovered period.

`api.live_coverage` lists the periods. `api.stream_summary` shows the
coverage of each stream from feeders and backfillers together.
`api.coverage_timeline` lists both kinds of periods for timelines.

A backfiller does not send heartbeats.

## Backfill

History is declarative ([ADR 0010](../adr/0010-declarative-history.md)).
Someone requests it, and backfillers load what is missing:

```sql
-- operator: request one or more streams for a period of any length.
-- The result has one history request for each stream.
select api.add_history_request($instrument_id, '{trade}', '2026-06-01', '2026-07-01');

-- board: requested, covered and missing periods
select * from api.history_coverage;
```

A backfiller registers when it starts, with its capabilities and the settings
of `api.lease_history()` ([ADR 0012](../adr/0012-backfiller-registry.md)):

```sql
select api.register_backfiller('my-backfiller@host1',
         array['binance-spot'], array['trade']::market.stream[], '1.0',
         '{"max_span": "1 day", "heartbeat_interval": "30 seconds"}');
```

The name identifies the backfiller in all later calls. Then it sends
`select api.backfiller_heartbeat('my-backfiller@host1')` every
`heartbeat_interval`, and works in a loop:

1. Lease the next missing period that it can load. The result has a new
   `writer_id`, the instrument, the stream, the period and the publish delay.
   No row means no work:
   ```sql
   select * from api.lease_history('my-backfiller@host1');
   ```
2. Write only the rows inside the period, with this `writer_id`, in short
   transactions.
3. Finish the run. This also closes the writer:
   ```sql
   select api.finish_backfill($writer_id, $rows);              -- success: the period is covered
   select api.finish_backfill($writer_id, $rows, 'error text'); -- failure: the period is missing again
   ```

When there is no work, wait for `NOTIFY empiriq_history` or for a timer.

- **Retries.** A failed period can be leased again after `retry_after`
  (default 10 minutes).
- **Abandoned runs.** If a backfiller sends no heartbeat for `stale_after`
  (default 5 minutes), its running runs are marked `abandoned`, and their
  writers are closed. Then their periods are leased again.
- **Paused requests.** An operator can pause a request
  (`api.pause_history_request()`). Backfillers get no new runs for it until
  it is resumed.
- **Source units.** If the source delivers fixed units, for example daily
  files, read the units that overlap the period and skip rows outside it.
- **Manual runs.** `api.start_backfill()` starts a run for a given period
  without a request. Its period counts as covered too.

**Publish delay.** Historical sources have the trade time, but not the time
when the venue published the trade. A backfiller sets
`event_time = trade_time + publish_delay`. empiriq measures the delay from
live trades of the venue ([ADR 0019](../adr/0019-measured-publish-delay.md)):

- the 99th percentile of `event_time - trade_time` over the newest live
  trades, at most 10000 for each instrument, in the last 7 days;
- at least 1000 trades, measured again when older than one hour;
- without a measurement, the setting `publish_delay` of the backfiller, else
  0.

Each run records its delay in `market.backfill_run`. `api.publish_delay`
shows the measurements; `api.measure_publish_delay(venue_id)` measures now.

**Gaps.** `api.trade_gaps()` returns the ranges of source trade ids that no
row covers in a time range:

```sql
select * from api.trade_gaps($instrument_id, $from, $to);
-- first_missing_id | last_missing_id | after_time | before_time
```

A gap is not always lost data. Some sources never publish some trades, so
their ids have gaps also in the source. For example, a history archive can skip
single ids all day long. Before you load a gap again, compare it with the gaps
of the source for the same period:

- The source has the same gaps: the data is complete. Loading again does not
  help.
- The source has the ids: rows were lost, for example by a live feeder during a
  disconnect. Request the period with `api.add_history_request()`. Duplicates are
  skipped. A period that a done backfill run already covers is not loaded
  again.

**Retry.** A failed run blocks its period for `retry_after`. `api.history_request`
shows the failed runs of a request (`failed_runs`) and the newest error
(`last_error`). To load the failed periods now, call
`api.retry_history_request(id)`. Backfill runs themselves are internal
([ADR 0011](../adr/0011-board.md)).

**Depth.** Venues usually do not provide historical depth snapshots. The
history of depth comes from the recordings of the live feeder or from a data
vendor.

## What strategies get

| Function | Returns |
|----------|---------|
| `api.trades(instrument_id, from, to, as_of)` | Visible trades with `trade_time` in `[from, to)`, oldest first: `trade_time`, `first_trade_id`, `last_trade_id`, `price`, `qty`, `aggressor`. The strategy builds bars from them ([ADR 0013](../adr/0013-strategies-build-bars.md)). |
| `api.last_quote(instrument_id, as_of)` | The best bid and best ask (`api.quote`) of the last visible depth snapshot. All fields are null if there is no snapshot. |
| `api.depth_levels(instrument_id, as_of)` | All price levels of that snapshot: `side`, `level`, `price`, `qty`, `notional`, `cum_qty`, `cum_notional`. |

A row is visible if `event_time <= as_of`. Inside a frame, its `seq` must also
be at most the cutoff of its stream. Inside a frame, these functions raise an
error for a stream without a subscription (`api.subscribe()`).

To build bars of fixed periods, group the trades by
`date_bin(interval, trade_time, '2000-01-01Z')`. Use only closed periods: the
period must end at or before `as_of`. `strategies.sma_cross` in
`migrations/0008_example_strategies.sql` shows the pattern.

## What the board gets

`api.trade_buckets(instrument_id, from, to, bucket)` groups trades with
`trade_time` in `[from, to)` into *trade buckets* for charts. Each row has:

- `ts`, the start of the period, aligned to UTC;
- `open`, `high`, `low`, `close`, `volume`, `notional` and the number of
  source `trades`;
- `buy_volume` and `sell_volume` by aggressor;
- `live_trades` and `history_trades`: source trades from feeders and from
  backfillers.

The function reads all rows at the time of the call. Frames and `as_of` do
not apply. The first and the last bucket can be partial. A period without
trades has no row. More than 2000 buckets are an error. Only the operator can
call the function.

## Example: write trades with pgx

This is a sketch for a Go feeder with `github.com/jackc/pgx/v5`. It uses bulk
`COPY` into a temporary staging table. Then one
`INSERT ... ON CONFLICT DO NOTHING` moves the rows. The database assigns `seq`
and `received_at`. The staging table has `aggressor` as `text`, so `COPY` does
not need to know the enum type.

```go
// num converts a float from the venue to an exact decimal (shortest round-trip form).
func num(f float64) pgtype.Numeric {
	var n pgtype.Numeric
	_ = n.Scan(strconv.FormatFloat(f, 'f', -1, 64))
	return n
}

// aggressor returns the order side of the taker, or nil if unknown.
func aggressor(t Trade) any {
	if t.IsBuyerMaker {
		return "sell"
	}
	return "buy"
}

// writerID comes from api.register_writer() or api.start_backfill().
func writeTrades(ctx context.Context, conn *pgx.Conn, writerID int64, ids map[string]int64, batch []Trade) error {
	tx, err := conn.Begin(ctx)
	if err != nil {
		return err
	}
	defer tx.Rollback(ctx)

	if _, err := tx.Exec(ctx, `create temp table if not exists trade_in (
		writer_id bigint, instrument_id bigint, first_trade_id bigint, last_trade_id bigint,
		event_time timestamptz, trade_time timestamptz, price numeric, qty numeric, aggressor text
	) on commit delete rows`); err != nil {
		return err
	}

	cols := []string{"writer_id", "instrument_id", "first_trade_id", "last_trade_id",
		"event_time", "trade_time", "price", "qty", "aggressor"}
	_, err = tx.CopyFrom(ctx, pgx.Identifier{"trade_in"}, cols,
		pgx.CopyFromSlice(len(batch), func(i int) ([]any, error) {
			t := batch[i]
			return []any{writerID, ids[t.Symbol], t.FirstTradeID, t.LastTradeID,
				time.UnixMilli(t.EventTime), time.UnixMilli(t.TradeTime),
				num(t.Price), num(t.Quantity), aggressor(t)}, nil
		}))
	if err != nil {
		return err
	}

	if _, err := tx.Exec(ctx,
		`insert into market.trade (writer_id, instrument_id, first_trade_id, last_trade_id,
		                           event_time, trade_time, price, qty, aggressor)
		 select writer_id, instrument_id, first_trade_id, last_trade_id,
		        event_time, trade_time, price, qty, aggressor::core.order_side
		   from trade_in
		 on conflict do nothing`); err != nil {
		return err
	}
	return tx.Commit(ctx)
}
```

Depth snapshots work the same way with `market.depth`. Each book side becomes
two slices:

```go
var bidPrice, bidQty []pgtype.Numeric
for l := range d.Bids() {
	bidPrice = append(bidPrice, num(l.Price))
	bidQty = append(bidQty, num(l.Quantity))
}
// Do the same for d.Asks(). Then COPY (writer_id, instrument_id, event_time, bid_price, bid_qty, ask_price, ask_qty).
```
