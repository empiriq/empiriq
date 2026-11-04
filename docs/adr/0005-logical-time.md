# 0005. Logical time

**Status:** Proposed

**Related:**

- Refined by [0013](0013-strategies-build-bars.md): `api.bars()` is replaced by `api.trades()`.

## Context

A backtest is useful only if two conditions are true. The strategy cannot see
the future. And the backtest runs the same code as live trading.

## Decision

- Each frame has an `as_of`. Strategies get it as `ctx.as_of`. `api.now()`
  returns it from a setting that the engine sets for the transaction.
- `as_of` increases from frame to frame of an account. Two frames of an
  account never have the same `as_of`.
- Market data is visible after the venue published it: `event_time <= as_of`
  ([ADR 0007](0007-market-data-model.md)). Inside a frame, the cutoffs of
  [ADR 0008](0008-frames.md) also apply.
- Helper functions use `as_of`. `api.bars()` returns only periods that are
  closed at `as_of` (`ts + interval <= as_of`).
- The modes `live`, `paper` and `backtest` differ in only two things: who sets
  `as_of`, and which feeders and reconcilers are registered. A backtest has no
  feeders: a backfiller loads the history before it starts.

## Consequences

- One strategy function works for research and for production.
- Strategies that query `market.*` directly can still see future data. A
  possible future protection: row-level security on `market.*` for the
  strategy role, based on `empiriq.as_of`.
- The frame overhead limits backtest speed. Each step takes a few
  transactions.
