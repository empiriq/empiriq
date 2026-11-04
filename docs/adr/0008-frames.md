# 0008. Frames: reconciliation drives strategies, cutoffs make them deterministic

**Status:** Proposed

**Related:**

- Supersedes [0003. Cycle barrier with NOTIFY and a trigger](0003-cycle-barrier.md).
- Refined by [0009](0009-writers.md): cutoffs for each writer.
- Refined by [0013](0013-strategies-build-bars.md): `api.bars()` is replaced by `api.trades()`.
- Refined by [0014](0014-live-coverage.md): live coverage.
- Refined by [0015](0015-strategies-run-as-strategy-role.md): strategies run as `empiriq_strategy`.

## Context

ADR 0003 used a global cycle: a scheduler started it, every worker sent an
ack, and a barrier then ran all strategies. This model has four problems:

- Something must call `api.start_cycle()`, but no service does.
- Feeders wait for a cycle, although market data arrives all the time.
- One slow venue delays the strategies of all accounts.
- Live trading and a backtest can see different data. A trade that reaches
  the database late is invisible in the live run, but visible in a later
  backtest with the same `as_of`.

## Decision

### Feeders write all the time

A feeder starts to write market data when its service starts. It does not
register and does not send an ack. The data of a feeder serves live trading
and later backtests. A backfiller adds history before a strategy starts, and
it fills gaps.

`market.watermark` becomes a heartbeat: for each stream, the feeder updates
`complete_until` also when there are no events. In a quiet market the
strategy still runs, on unchanged data. A stale watermark shows a broken
feeder. Detection of a broken feeder belongs to the container level, later.

### A reconciliation starts frames

The reconciler is the clock of an account. After each pass over an account, it
calls `api.reconciled(account_id, as_of)`. This inserts a row into
`core.reconciliation` and runs one **frame** for each running strategy of the
account. Then the planners run for the account, and
`NOTIFY empiriq_frame` wakes the reconciler for its next pass.

- `as_of` of a frame is the `as_of` of the reconciliation. It increases for
  each account.
- Each account has its own rhythm. A slow venue delays only its own accounts.
- No scheduler is needed. In a backtest, the simulated reconciler moves
  `as_of` through history.
- If the venue is not reachable, the reconciler does not call
  `api.reconciled`, so no frame runs on stale account state.

### Cutoffs make frames repeatable

Every row in `market.trade` and `market.depth` has a `seq`, assigned at insert.
One writer per stream commits in order, so `seq` grows in commit order inside
a stream. A stream is one instrument and one event type (`trade` or `depth`).

At frame start, the engine records a **cutoff** for each stream that the
strategy subscribed to: the highest `seq` in the database. The frame sees a row
only if both conditions are true:

- `event_time <= as_of`;
- `seq <= cutoff` of its stream.

A replay of a recorded frame uses the same cutoffs, so it sees exactly the same
rows. Late rows are invisible in both runs.

Accounts in the `backtest` mode work on backfilled history. They have no
recorded cutoffs, so `event_time <= as_of` alone decides. This is
deterministic too, because history does not change during a backtest.

### Strategies subscribe to streams

A strategy lists its streams in `core.strategy_stream` (instrument and event
type). The engine records cutoffs only for these streams. Inside a frame, the
helper functions (`api.bars()`, `api.last_quote()`, `api.depth_levels()`)
raise an error for a stream without a subscription.

### Reconcilers write an event journal

Reconcilers report events with an `event_time`: order accepted, order
rejected, fill, balance change, position change. `core.account_event` stores
each event. The tables of observed state are updated from the same report.
`core.reconciliation` stores the `seq` of the last account event. So it is
known which account events a frame was based on.

### A declarative table controls strategies

`core.strategy` works like a Kubernetes Deployment:

- `desired_status` is `running` or `stopped`. Only running strategies get
  frames.
- `on_stop` defines what happens to the desired state when the strategy
  stops: `keep` leaves it, `cancel` removes it, so the reconciler cancels the
  open orders.
- The mode (`live`, `paper`, `backtest`) is a property of the account.
  Starting a backtest means: create an account in the `backtest` mode, add
  strategies to it, start its simulated reconciler.

`api.strategy_status` shows the observed side: the last frame, its status and
its error.

## Consequences

- The global cycle, the worker registry, `api.ack()`, `api.nack()` and
  `api.start_cycle()` are removed.
- Frames run in the transaction of `api.reconciled()`. A slow strategy makes
  the reconciler slow. A separate runner remains an option.
- Cutoffs need an index on `(instrument_id, seq)`. Their cost is one index
  lookup per subscribed stream and frame.
- The observed state that a strategy reads is the current state. Rebuilding
  it from the event journal for an exact replay is future work.
- Strategies on more than one account need a rule for which reconciliations
  start their frames (open question).
- Stopping with "close all positions" (`flatten`) is not supported yet.
