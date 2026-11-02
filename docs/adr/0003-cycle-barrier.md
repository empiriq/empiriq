# 0003. Cycle barrier with NOTIFY and a trigger

**Status:** Superseded by [0008](0008-frames.md).

## Context

Before strategies run, two things must be complete. Feeders must store all
market data up to `as_of`. Reconcilers must reconcile every account. Workers are
external processes.

## Decision

- `api.start_cycle()` inserts a cycle and one `cycle_task` for each enabled
  worker. It sets `cycle.pending` to the number of tasks. Then it sends
  `NOTIFY empiriq_cycle {cycle_id, phase, as_of, mode}`.
- A worker ends its task with `api.ack()` or `api.nack()`.
- An `AFTER UPDATE` trigger on `cycle_task` runs
  `UPDATE cycle SET pending = pending - 1 ... RETURNING pending`. The row lock
  makes concurrent acks run one after the other. The transaction that sees `0`
  runs the decide phase.
- A watchdog (`api.expire_tasks()`) sets late tasks to `timeout`.
- NOTIFY is only a signal. After a reconnect, workers call
  `api.pending_tasks()`.

### Why a counter, and not `count(*) where status = 'pending'`

Two workers can ack at the same time. Then each transaction cannot see the
uncommitted update of the other one. Both count one pending task, and the
cycle never continues. With an `UPDATE` on one row, the second transaction
waits. Then it reads the committed value.

## Consequences

- No polling. Correctness does not need an external orchestrator.
- The decide phase runs in the transaction of the last ack. A slow strategy
  makes that ack slow. Strategy code runs with the privileges of the function
  owner. A separate runner can change this (open question).
- A partial unique index allows only one cycle at a time.
