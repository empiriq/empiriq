# 0013. Strategies build bars, the console gets trade buckets

**Status:** Proposed. Refines [0005](0005-logical-time.md),
[0007](0007-market-data-model.md) and [0008](0008-frames.md).

## Context

`api.bars()` built bars for strategies in one fixed way: time periods
aligned to UTC, open, high, low, close by `trade_time`, and no bar for a
period without trades. There are many other ways to build a bar:

- bars of a fixed number of trades, of a fixed volume or of a fixed notional;
- bars that carry the last close into a period without trades;
- bars with a volume-weighted average price or with buy and sell volume.

Each way needs a new function or new parameters in `api`. The console also
needs bars for charts, but with other rules: an explicit time range, partial
periods, no frames, and access for the operator.

## Decision

### Strategies read trades and build their own bars

- `api.bars()` and the type `api.bar` are removed.
- `api.trades(instrument_id, from, to, as_of)` returns the visible trades
  with `trade_time` in `[from, to)`, oldest first. It applies the same
  visibility rules as the other helper functions: `event_time <= as_of` and,
  inside a frame, the cutoffs of [ADR 0008](0008-frames.md).
- A strategy groups the trades into bars in SQL. The strategy decides which
  periods are closed. The example `strategies.sma_cross` uses only periods
  that end before `as_of`.

### The console reads trade buckets

- `api.trade_buckets(instrument_id, from, to, bucket)` groups trades into
  periods of length `bucket`, aligned to UTC.
- Each row has open, high, low, close, volume, notional, the number of source
  trades, buy and sell volume by aggressor, and the number of source trades
  from feeders and from backfillers.
- `api.trade_buckets()` reads all rows at the time of the call. Frames and
  `as_of` do not apply. The first and the last bucket can be partial.
- It refuses more than 2000 buckets, so a chart cannot read a year of trades
  in one-second buckets.
- Only the operator can call it. Strategies must use `api.trades()`.

## Consequences

- A new kind of bar needs no change of `api`. It is SQL in a strategy, or a
  function in the schema `strategies` that several strategies share.
- Strategies repeat the grouping code. The example strategy shows the
  pattern to copy.
- A strategy that reads many trades is slower than with materialized bars.
  The open question of materialized bars from [ADR 0007](0007-market-data-model.md)
  stays open.
- A chart in the console and a strategy can show different bars for the same
  period: the chart sees late trades that a frame did not see. This is
  intended. The chart shows what the database has now.
- `api.bars()` and `api.bar` are removed. This is a breaking change of `api`.
