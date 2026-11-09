# 0005. Logical time

**Status:** Proposed

**Related:**

- Refines [0000. empiriq: a trading platform around PostgreSQL, built from services](0000-empiriq.md).

## Context

A backtest is useful only if two conditions are true. The strategy cannot see
the future. And the backtest runs the same code as live trading.

A trade has two times: when it happened on the venue, and when the venue
published it. A live strategy learns about a trade only after the venue
published it.

## Options

- **Wall clock.** Strategies read the current time. A backtest replaces the
  clock outside the database. Every helper and every strategy must then avoid
  the real clock, and nothing checks it.
- **Logical time.** The engine gives each step of a strategy a time. All
  helpers use it. The strategy never reads the wall clock.

For visibility of market data:

- **By trade time.** Simple, but a backtest sees trades that a live strategy
  saw only later.
- **By publication time.** A backtest sees what a live strategy saw.

## Decision

- Each frame has a logical time, `as_of`. Strategies and helpers use it and
  never the wall clock.
- `as_of` only increases. Two frames never have the same `as_of`.
- Market data is visible only after the venue published it at `as_of`. The
  trade time only puts a trade into a period.
- A strategy uses only periods that are closed at `as_of`.
- A live run and a replay differ in only two things: how `as_of` moves, and
  where the market data comes from.

## Consequences

- One strategy function works for research and for production.
- Feeders and backfillers must store the publication time of each event.
- Only the helpers enforce visibility. A strategy that reads market data
  another way can see the future.
- Each step of a backtest costs a frame. This limits the speed of backtests.
