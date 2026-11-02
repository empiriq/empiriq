# 0007. Market data: raw trades and depth snapshots, all other values derived

**Status:** Proposed. Refined by [0013](0013-strategies-build-bars.md): strategies build bars from `api.trades()`.

## Context

Feeders send two event types:

- trades (`Trade`): an event time, a trade time and a range of source trade
  ids;
- depth snapshots (`Depth`): the top N price levels with price, quantity and
  notional.

Strategies need bars, the best bid and best ask, and depth. Backtests must not
see data before the venue published it.

## Decision

- Store only what feeders observe:
  - `market.trade`: one row for each trade;
  - `market.depth`: one row for each depth snapshot. Price levels are parallel
    `numeric[]` arrays. Index 1 is the best level.
- Feeders and backfillers use **raw trades**: one source trade in each row,
  so `first_trade_id = last_trade_id`. Live and historical data then have the
  same shape, and the same trade has the same key in both. The columns stay
  ranges, so an aggregated source remains possible later.
- `aggressor` stores the order side of the taker, if the source gives it.
- **Visibility uses `event_time`**, the publish time. `trade_time` only puts
  trades into periods for bars.
- Idempotency comes from the identity at the source. The key of a trade is
  `(instrument_id, first_trade_id)`. The key of a depth snapshot is
  `(instrument_id, event_time)`.
- Functions calculate derived values on read. The database does not store
  them:
  - bars: `api.bars`;
  - best bid and best ask: `api.last_quote`;
  - notional and cumulative depth for each price level: `api.depth_levels`.
- Prices and quantities are `numeric`. Feeders convert floats to exact
  decimals.
- Check constraints validate depth snapshots: sorted, positive, equal lengths,
  not crossed.

## Consequences

- There is one authoritative data set. Every strategy and dashboard sees the
  same bars and best prices. To correct a bar, correct the trades. There are
  no aggregates to rewrite.
- Each call to `api.bars` reads all trades in the window. This is acceptable
  for minute bars over short windows. Long windows or many strategies will
  need materialized bars (open question).
- Depth snapshots are large: N levels multiplied by the update rate. They need
  a retention policy.
- Raw trades give more rows than aggregated trades.
- With `aggressor`, trade-flow features (imbalance of buys and sells) are
  possible. The Go `Trade` event needs a field for it.
