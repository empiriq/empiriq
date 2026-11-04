# 0008. Frames: reconciliation drives strategies, cutoffs make them deterministic

**Status:** Proposed

**Related:**

- Supersedes [0003. Cycle barrier with NOTIFY and a trigger](0003-cycle-barrier.md).
- Refines [0000. empiriq: a trading platform around PostgreSQL, built from services](0000-empiriq.md).
- Refined by [0009](0009-writers.md): cutoffs for each writer.
- Refined by [0013](0013-strategies-build-bars.md): `api.bars()` is replaced by `api.trades()`.
- Refined by [0014](0014-live-coverage.md): live coverage.
- Refined by [0015](0015-strategies-run-as-strategy-role.md): strategies run as `empiriq_strategy`.
- Refined by [0022](0022-frames-belong-to-a-world.md): frames belong to a world, the last reconciler closes a frame.

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

Feeders write the streams of the live requests all the time, not in frames
([ADR 0016](0016-live-requests.md)). Their data serves live trading and later
backtests. A backfiller adds history and fills gaps.

For each stream, a feeder also reports a heartbeat, the watermark: it updates
`complete_until` also when there are no events
([ADR 0014](0014-live-coverage.md)). In a quiet market the strategies still
run, on unchanged data. A stale watermark shows a broken feeder.

### Reconciliations drive frames

Reconcilers set the rhythm. A frame is one step of a world. When the last
reconciler of the world confirms the frame, the frame ends: the strategies of
the world run, then the planners, and `NOTIFY empiriq_frame` opens the next
frame ([ADR 0022](0022-frames-belong-to-a-world.md)).

- No scheduler is needed. In a backtest, the simulated reconcilers move
  `as_of` through history.
- A reconciler confirms every frame, also when the venue did not respond.

### Cutoffs make frames repeatable

Every row in `market.trade` and `market.depth` has a writer and a `seq`,
assigned at insert. At frame start, the engine records a **cutoff** for each
subscribed stream and each open writer: its highest `seq`. The frame sees a
row only if `event_time <= as_of` and the cutoff of its writer allows it.
[ADR 0009](0009-writers.md) has the rules for open and closed writers.

A replay of a recorded frame uses the same cutoffs, so it sees exactly the same
rows. Late rows are invisible in both runs.

A world in the `backtest` mode works on backfilled history. Its frames record
no cutoffs, so `event_time <= as_of` alone decides. This is deterministic too,
because history does not change during a backtest.

### Strategies subscribe to streams

A strategy lists its streams in `core.strategy_stream` (instrument and event
type). The engine records cutoffs only for these streams. Inside a frame, the
helper functions (`api.trades()`, `api.last_quote()`, `api.depth_levels()`)
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
- The mode (`live`, `backtest`) is a property of the world
  ([ADR 0022](0022-frames-belong-to-a-world.md)). Starting a backtest means:
  create a world in the `backtest` mode with simulated accounts, add
  strategies to them, start the simulated reconcilers and the world.

`api.strategy_status` shows the observed side: the last frame, its status and
its error.

## Consequences

- The global cycle, the worker registry, `api.ack()`, `api.nack()` and
  `api.start_cycle()` are removed.
- Frames run in the transaction of the last `api.reconciled()`. A slow
  strategy makes that reconciler slow. A separate runner remains an option.
- Cutoffs need an index on `(instrument_id, writer_id, seq)`. Their cost is
  one index lookup for each subscribed stream and open writer in a frame.
- The observed state that a strategy reads is the current state. Rebuilding
  it from the event journal for an exact replay is future work.
- How a strategy sends intents to several accounts of its world is an open
  question.
- Stopping with "close all positions" (`flatten`) is not supported yet.
