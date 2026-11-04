# 0020. Backfill runs are internal

**Status:** Proposed

**Related:**

- Refines [0010. Declarative history: requests, coverage and leases](0010-declarative-history.md).
- Refines [0011. Board: one user interface, only through `api`](0011-board.md).

## Context

`api.backfill_run` showed every backfill run to the operator, and
`api.retry_backfill_run()` and `api.discard_backfill_run()` acted on single
runs. Only the board used them. Backfillers use runs only through
`api.lease_history()` and `api.finish_backfill()`.

An operator thinks in history requests: which history is wanted, and why it
is not complete yet. How a request is cut into runs, their writers and their
retries is the work of empiriq and the backfillers.

## Decision

- `api.backfill_run`, `api.retry_backfill_run()` and
  `api.discard_backfill_run()` are removed. `market.backfill_run` stays: it is
  the coverage, it blocks leased periods, and each run is a writer.
- `api.history_request` shows `failed_runs` and `last_error`: the failed runs
  that overlap the missing part of the request, and the newest error.
- `api.retry_history_request(id)` lets backfillers load the failed periods of
  a request now, without waiting for `retry_after`.
- There is no discard. The status `discarded` is removed. The owner can delete
  the rows of a run in SQL if it is really needed.

## Consequences

- The board has no page of backfill runs. The page of a history request
  shows why it is incomplete and offers Retry.
- `api.backfiller` still counts runs (`running`, `done_24h`, `failed_24h`,
  `rows_24h`). It reads the table, not a view.
- Debugging of single runs uses SQL on `market.backfill_run`, as the owner.
- This is a breaking change of `api`.
