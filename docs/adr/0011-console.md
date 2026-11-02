# 0011. Console: one user interface, only through `api`

**Status:** Proposed. Refines [0010](0010-declarative-history.md). Refined by [0020](0020-backfill-runs-are-internal.md): no backfill runs in the console.

## Context

People need to see the state of empiriq and to change desired state: request
history, start and stop strategies. Until now the plan was a read-only
dashboard with the role `empiriq_reader`, and a second role `empiriq_operator`
for control actions (ADR 0010). The dashboard also read `market.*` directly.

This plan has three problems:

- Two roles for one user interface. The interface needs both roles, so the
  difference protects nothing.
- Reads from `market.*` tie the interface to tables that can change.
- The control functions cover only part of the work. For example, nobody can
  withdraw a request, retry a failed run now, or undo a bad run.

## Decision

### The console is a service

The **console** is the user interface of empiriq. It shows the state and
changes desired state. It is a separate service in its own repository, like
every other service (one service, one repository). The first console is built
with Grafana.

### Only through `api`

The console reads only views and functions of `api`. It changes state only by
calling functions of `api`. It never reads or writes `market.*` or `core.*`.

So `api` gets views shaped for display:

| View                  | Shows                                                              |
|-----------------------|--------------------------------------------------------------------|
| `api.history_request` | History requests, with the part of the period that is still missing |
| `api.backfill_run`    | Backfill runs, with duration and rows per second                    |
| `api.writer`          | Writers and whether they are open                                   |
| `api.stream_health`   | Live streams: lag of `complete_until` and age of the last heartbeat |

And control functions:

| Function                    | Does                                                                  |
|-----------------------------|-----------------------------------------------------------------------|
| `api.withdraw_history_request()`    | Deletes a history request. Loaded rows stay.                          |
| `api.retry_backfill_run()`      | Lets backfillers lease the period of a failed run now                 |
| `api.discard_backfill_run()`    | Deletes the rows of a finished run. Its period is missing again.      |

`api.add_history_request()` gets the argument `p_requested_by`. The console passes
the name of its user, so the request shows the person and not the database
user.

### One role

`empiriq_operator` is the only role for people and the console. It reads all
of `api.*` and calls the control functions. The role `empiriq_reader` is
removed.

The database does not separate users of the console. If the console needs
users with fewer rights, the console controls that itself.

## Consequences

- The console can change without changes to tables, and tables can change
  without changes to the console, as long as `api` stays the same.
- `api` grows with every new screen. A view for display is part of the
  contract, so its changes are breaking changes.
- Everybody with access to the console can change desired state. There is no
  read-only access to the database for people. A read-only role can come back
  later, if a use case needs it.
- `api.discard_backfill_run()` deletes rows that past frames may have seen. A
  backtest over the period gives other results after a new load. Use it for
  bad data only.
- A failed run counts as failed in `api.history_coverage` only while its
  period overlaps a missing period. After a successful retry, or after the
  request is withdrawn, the stream shows no failures.
