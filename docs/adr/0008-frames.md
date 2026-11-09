# 0008. Frames: reconciliation drives strategies, cutoffs make them deterministic

**Status:** Proposed

**Related:**

- Supersedes [0003. Cycle barrier with NOTIFY and a trigger](0003-cycle-barrier.md).
- Refines [0000. empiriq: a trading platform around PostgreSQL, built from services](0000-empiriq.md).

## Context

ADR 0003 used a global cycle: a scheduler started it, every worker confirmed
it, and a barrier then ran all strategies. This model has four problems:

- Something must start a cycle, but no service does.
- Feeders wait for a cycle, although market data arrives all the time.
- One slow venue delays the strategies of all accounts.
- Live trading and a backtest can see different data. A trade that reaches
  the database late is invisible in the live run. A later backtest with the
  same `as_of` sees it.

## Options

What starts a frame:

- **A scheduler** with a fixed interval. One more component. Strategies can
  run on observed state that is not reconciled yet.
- **The reconcilers.** A frame ends when its reconciliations are complete.
  No component is added, and strategies always see reconciled state.

How a frame becomes repeatable:

- **Wait for feeders** in the barrier. Feeders become part of every frame,
  and a slow feeder stops trading.
- **Record what the frame saw.** At the end of a frame, store a cutoff for
  each stream. A replay uses the same cutoffs.

## Decision

- Feeders write market data all the time and take no part in frames. They
  report a watermark also in a quiet market.
- Reconcilers set the rhythm. When the last reconciliation of a frame is
  complete, the strategies run and the next frame opens. No scheduler.
- A reconciler completes every reconciliation, also when the venue did not
  respond.
- At the end of a frame, before the strategies run, the engine records a
  cutoff for each stream. A frame sees only rows below its cutoffs. A replay
  uses the same cutoffs.
- A strategy declares the streams that it reads. Cutoffs exist only for these
  streams.
- Reconcilers report account events into a journal. Each reconciliation
  records the last event that it includes.
- Strategies are controlled by desired state: running or stopped, and what
  happens to their desired state when they stop.

## Consequences

- The global cycle and the worker registry are gone.
- The strategies run in the transaction of the last reconciliation. A slow
  strategy makes that reconciler slow.
- A late row is invisible in the frame and in its replay.
- Cutoffs cost one index lookup for each subscribed stream.
- Strategies read the current observed state. An exact replay of observed
  state from the journal is future work.
