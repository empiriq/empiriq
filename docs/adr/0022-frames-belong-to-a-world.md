# 0022. Frames belong to a world, the last reconciler closes a frame

**Status:** Proposed

**Related:**

- Refines [0005. Logical time](0005-logical-time.md).
- Refines [0008. Frames: reconciliation drives strategies, cutoffs make them deterministic](0008-frames.md).

## Context

With [ADR 0008](0008-frames.md), each account has its own rhythm. A
reconciliation of an account starts the frames of the strategies on that
account. This has three problems:

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

A **world** is a set of accounts with one clock and one mode. Each account
belongs to one world.

- The mode of a world is `live` or `backtest`. A live world follows the wall
  clock. A backtest world moves through history.
- Paper trading is a live world with simulated accounts. The simulator is a
  later decision.
- Several backtest worlds can run next to a live world in one database. Market
  data is shared by all worlds.

### A frame is a step of a world

A **frame** is one step of a world at one `as_of`. The database opens a frame
with `pending` = the number of accounts of the world. Then it sends
`NOTIFY empiriq_frame {world_id, frame_id, as_of}`.

- `as_of` increases from frame to frame of a world.
- In a live world, `as_of` of a new frame is `clock_timestamp()`. In a
  backtest world, it is the `as_of` of the previous frame plus the step of the
  world.
- An operator starts a world with `api.start_world(world_id)`. It opens the
  first frame if no frame of the world is open.
- An account that joins a world takes part from the next frame.

### The last reconciler closes the frame

For each frame, a reconciler reconciles each of its accounts. Then it calls
`api.reconciled(account_id, frame_id)`. This records the reconciliation with
the `seq` of the last account event, as before.

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
In a backtest world, simulated reconcilers respond at once, so the world runs
as fast as the database.

### The frame handler is a separate decision

What `core.on_frame()` does is not part of this decision. Its first version
does what `core.run_frames()` does today: it runs the running strategies of
the world and then the planners. A later ADR decides how strategies run in a
frame.

## Consequences

- A strategy can read all accounts of its world at one `as_of`. How it
  writes intents to several accounts is part of the handler decision.
- The reconciler no longer chooses `as_of`. It listens on `empiriq_frame`
  and calls `api.reconciled(account_id, frame_id)` for each of its accounts.
  This is a breaking change of `api`.
- A reconciler that stops or hangs stops its world: the frame never ends.
  Detection and a deadline are open questions. A venue that does not respond
  is not such a case: the reconciler still calls `api.reconciled()`.
- The slowest venue of a world sets the rhythm for all strategies of that
  world.
- The handler runs in the transaction of the last reconciler. A slow handler
  makes that reconciler slow, as today.
- The mode moves from the account to the world. `core.frame` today stores
  one run of one strategy; its name and shape change with the handler
  decision.
- A world without accounts has no frames.
