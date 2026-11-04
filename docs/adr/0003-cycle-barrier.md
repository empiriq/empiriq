# 0003. Cycle barrier with NOTIFY and a trigger

**Status:** Superseded by [0008](0008-frames.md).

Kept as a record of an approach that was tried. [ADR 0008](0008-frames.md)
says why it was removed. [ADR 0022](0022-frames-belong-to-a-world.md) brings
back a barrier, for each world, without these problems.

## Context

Before strategies run, feeders must store all market data up to `as_of`, and
reconcilers must reconcile every account.

## Decision

- A global cycle: `api.start_cycle()` creates one task for each worker and
  sends `NOTIFY empiriq_cycle`.
- Each worker ends its task with `api.ack()` or `api.nack()`. A counter in
  the row of the cycle finds the last ack. That transaction runs the
  strategies.
- A watchdog (`api.expire_tasks()`) ends late tasks.

## Consequences

- No polling and no external orchestrator.
- Nothing calls `api.start_cycle()`, feeders wait for cycles, and the slowest
  worker delays all accounts ([ADR 0008](0008-frames.md)).
