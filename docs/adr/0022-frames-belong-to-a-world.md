# 0022. Frames belong to a world, the last reconciler closes a frame

**Status:** Proposed

**Related:**

- Refines [0005. Logical time](0005-logical-time.md).
- Refines [0008. Frames: reconciliation drives strategies, cutoffs make them deterministic](0008-frames.md).

## Context

ADR 0008 decides that reconciliations drive frames. It does not decide which
reconciliations belong to one frame. The first version gave each account its
own rhythm: a reconciliation of an account started the strategies on that
account. This had three problems:

- A strategy cannot use more than one account. Arbitrage between venues needs
  the state of several accounts at the same `as_of`.
- In one frame, a strategy sees one account that was just reconciled. The
  other accounts can be in any state.
- In a backtest, each simulated account moves its own time. Several simulated
  accounts have no common clock.

## Options

- **One frame for each account.** Simple, but it has the problems above.
- **One global frame for all accounts.** One slow venue stops all
  strategies, like the cycle of [ADR 0003](0003-cycle-barrier.md). Live
  trading and backtests cannot run at the same time.
- **One frame for each group of accounts with one clock.** Accounts that must
  be seen together share a frame. Other groups run on their own.

## Decision

- A **world** is a set of accounts with one clock. Each account belongs to
  one world.
- The clock is `live` or `replay`. A live world follows the wall clock. A
  replay world moves through history step by step.
- A frame is one step of a world. `as_of` increases for each world.
- A frame opens one reconciliation for each account of its world. The last
  completed reconciliation ends the frame. A counter in the frame decides
  which reconciliation is the last. Counting the reconciliations would not
  work: two concurrent transactions do not see each other.
- Several worlds can run in one database at the same time. They share the
  market data.
- What runs at the end of a frame is a separate decision.

## Consequences

- A strategy sees all accounts of its world at one `as_of`.
- The slowest venue of a world sets the rhythm for that world only.
- A reconciler that stops or hangs stops its world. Detection and a deadline
  are open.
- The clock of the world replaces a mode for each account (live, paper,
  backtest).
- Backtests and live trading record cutoffs the same way. Visibility has no
  special case for backtests.
- How a strategy sends intents to several accounts is open.
