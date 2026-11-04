# 0011. Board: one user interface, only through `api`

**Status:** Proposed

**Related:**

- Refines [0010. Declarative history: requests, coverage and leases](0010-declarative-history.md).

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

### The board is a service

The **board** is the user interface of empiriq. It shows the state and
changes desired state. It is a separate service in its own repository, like
every other service (one service, one repository). The first board is built
with Grafana.

### Only through `api`

The board reads only views and functions of `api`. It changes state only by
calling functions of `api`. It never reads or writes `market.*` or `core.*`.

So `api` gets views shaped for display:

| View                  | Shows                                                                                                      |
|-----------------------|------------------------------------------------------------------------------------------------------------|
| `api.history_request` | History requests: the part of the period that is still missing, the failed runs in it and the newest error |
| `api.writer`          | Writers and whether they are open                                                                          |
| `api.stream_health`   | Live streams: lag of `complete_until` and age of the last heartbeat                                        |

And control functions:

| Function                         | Does                                                                                         |
|----------------------------------|----------------------------------------------------------------------------------------------|
| `api.withdraw_history_request()` | Deletes a history request. Loaded rows stay.                                                 |
| `api.retry_history_request()`    | Lets backfillers load the failed periods of a request now, without waiting for `retry_after` |

### Backfill runs are internal

The board shows history requests, not backfill runs. `market.backfill_run`
stays: it is the coverage, it blocks leased periods, and each run is a
writer. There is no function to discard a run. The owner can delete the rows
of a run in SQL if it is really needed.

`api.add_history_request()` gets the argument `p_requested_by`. The board passes
the name of its user, so the request shows the person and not the database
user.

### One role

`empiriq_operator` is the only role for people and the board. It reads all
of `api.*` and calls the control functions. The role `empiriq_reader` is
removed.

The database does not separate users of the board. If the board needs
users with fewer rights, the board controls that itself.

## Consequences

- The board can change without changes to tables, and tables can change
  without changes to the board, as long as `api` stays the same.
- `api` grows with every new screen. A view for display is part of the
  contract, so its changes are breaking changes.
- Everybody with access to the board can change desired state. There is no
  read-only access to the database for people. A read-only role can come back
  later, if a use case needs it.
- The board has no page of backfill runs. The page of a history request
  shows why it is incomplete and offers Retry. Debugging of single runs uses
  SQL on `market.backfill_run`, as the owner.
- A failed run counts as failed in `api.history_coverage` only while its
  period overlaps a missing period. After a successful retry, or after the
  request is withdrawn, the stream shows no failures.
