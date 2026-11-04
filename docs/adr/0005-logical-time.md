# 0005. Logical time

**Status:** Proposed

**Related:**

- Refines [0000. empiriq: a trading platform around PostgreSQL, built from services](0000-empiriq.md).
- Refined by [0013](0013-strategies-build-bars.md): `api.bars()` is replaced by `api.trades()`.
- Refined by [0022](0022-frames-belong-to-a-world.md): `as_of` increases for each world, not for each account.

## Context

A backtest is useful only if two conditions are true. The strategy cannot see
the future. And the backtest runs the same code as live trading.

## Decision

- Each frame has an `as_of`. Strategies get it as `ctx.as_of`. `api.now()`
  returns it from a setting that the engine sets for the transaction.
- `as_of` increases from frame to frame of a world
  ([ADR 0022](0022-frames-belong-to-a-world.md)). Two frames of a world never
  have the same `as_of`.
- Market data is visible after the venue published it: `event_time <= as_of`
  ([ADR 0007](0007-market-data-model.md)). Inside a frame, the cutoffs of
  [ADR 0008](0008-frames.md) also apply.
- Helper functions use `as_of`. A strategy builds bars from `api.trades()`
  only for periods that are closed at `as_of` (`ts + interval <= as_of`,
  [ADR 0013](0013-strategies-build-bars.md)).
- A live world and a replay world differ in only two things: how `as_of`
  moves, and where the market data comes from. A backtest has no
  feeders: a backfiller loads the history before it starts.

## Consequences

- One strategy function works for research and for production.
- Strategies that query `market.*` directly can still see future data. A
  possible future protection: row-level security on `market.*` for the
  strategy role, based on `empiriq.as_of`.
- The frame overhead limits backtest speed. Each step takes a few
  transactions.
