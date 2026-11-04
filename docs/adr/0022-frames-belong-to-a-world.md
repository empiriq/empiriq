# 0022. Frames belong to a world, the last reconciler closes a frame

**Status:** Proposed

**Related:**

- Refines [0005. Logical time](0005-logical-time.md).
- Refines [0008. Frames: reconciliation drives strategies, cutoffs make them deterministic](0008-frames.md).

## Context

Before this decision, each account had its own rhythm. A reconciliation of
an account started the frames of the strategies on that account. This had
three problems:

- A strategy cannot use more than one account. Arbitrage between venues needs
  the state of several accounts at the same `as_of`.
- In one frame, a strategy sees one account that was just reconciled. The
  other accounts can be in any state. The strategy logic must handle this.
- Each simulated reconciler moves its own time in a backtest. Several
  simulated accounts have no common clock.

A barrier for all participants was tried before, in
[ADR 0003](0003-cycle-barrier.md), and [ADR 0008](0008-frames.md) removed it.
Its problems do not come back here. Nobody has to start a frame: the last
reconciler opens the next one. Feeders do not take part: cutoffs make frames
repeatable without waiting for them. A slow venue delays only its own world,
not all accounts.

## Decision

### A world has one clock

A **world** is a set of accounts with one clock. Each account belongs to one
world.

- The **clock** of a world is `live` or `replay`. A live world follows the
  wall clock. A replay world moves through history step by step.
- A backtest runs in a replay world. Paper trading runs in a live world with
  simulated accounts. The simulator is a later decision.
- Several replay worlds can run next to a live world in one database. Market
  data is shared by all worlds.
- The owner creates worlds and their accounts in SQL (`core.world`,
  `core.account.world_id`). An operator starts a world.

### A frame is a step of a world

A **frame** is one step of a world at one `as_of`. The database opens a frame
with one open reconciliation for each account of the world and `pending` =
the number of accounts. Then it sends
`NOTIFY empiriq_frame {world_id, frame_id, as_of}`.

- `as_of` increases from frame to frame of a world.
- In a live world, `as_of` of a new frame is `clock_timestamp()`. In a
  replay world, it is the `as_of` of the previous frame plus the step of the
  world.
- An operator starts a world with `api.start_world(world_id, as_of)`. It opens
  the first frame if no frame of the world is open. A replay world starts at
  `as_of`; a live world ignores it and starts now.
- A replay world can end: after the frame at `replay_to`, it opens no frame.
- An account that joins a world takes part from the next frame.

### The last reconciler closes the frame

For each frame, a reconciler reconciles each of its accounts. Then it calls
`api.reconciled(account_id, frame_id)`. This completes the reconciliation of
the account in the frame, with the `seq` of the last account event. A second
call for the same account and frame is an error.

- The reconciler calls it for every frame and every account, also when the
  venue did not respond.
- The call decrements `pending` with
  `update ... set pending = pending - 1 returning pending`. The row lock
  orders concurrent calls, so exactly one transaction sees 0. A count of
  reconciliations would not work: two concurrent transactions do not see
  each other, and the frame would never close.
- The transaction that sees 0 is the end of the frame. In that transaction,
  the database calls the frame handler `core.on_frame(frame_id)`, opens the
  next frame and sends `NOTIFY empiriq_frame`.

No scheduler is needed: the slowest reconciler of a world sets its rhythm.
In a replay world, simulated reconcilers respond at once, so the world runs
as fast as the database.

### Cutoffs for each frame

When a frame ends, before its strategies run, the engine records the cutoffs
of the frame: for each stream that a running strategy of the world
subscribed to, and each writer with rows in it, its highest `seq`
([ADR 0009](0009-writers.md)). All strategies of the frame see the same rows.

Frames of both clocks record cutoffs. Then a replay world stays repeatable
also when a backfill loads history while it runs. After ADR 0009 a frame has
few cutoffs: one for each subscribed stream and service.

### Strategy runs

The run of one strategy in a frame is a **strategy run**
(`core.strategy_run`): its status, its error, the number of intents and its
duration. A failing run keeps the previous desired state of its strategy.

### The frame handler is a separate decision

What `core.on_frame()` does is not part of this decision. Its first version
records the cutoffs, runs the running strategies of the world, one strategy
run each, and then the planners of each account. A later ADR decides how
strategies run in a frame.

## Consequences

- A strategy can read all accounts of its world at one `as_of`. How it
  writes intents to several accounts is part of the handler decision.
- The reconciler no longer chooses `as_of`. It listens on `empiriq_frame`
  and calls `api.reconciled(account_id, frame_id)` for each of its accounts.
- A reconciler that stops or hangs stops its world: the frame never ends.
  Detection and a deadline are open questions. A venue that does not respond
  is not such a case: the reconciler still calls `api.reconciled()`.
- The slowest venue of a world sets the rhythm for all strategies of that
  world.
- The handler runs in the transaction of the last reconciler. A slow handler
  makes that reconciler slow, as today.
- The clock of the world replaces the mode of the account (`live`, `paper`,
  `backtest`).
- A frame of a replay world records cutoffs like a frame of a live world.
  Visibility has no special case for backtests.
- A world without accounts has no frames.
