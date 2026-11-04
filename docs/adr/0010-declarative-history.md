# 0010. Declarative history: requests, coverage and leases

**Status:** Proposed

**Related:**

- Refines [0009. Writers: cutoffs for each writer](0009-writers.md).
- Refined by [0011](0011-console.md): one role for the console, and more control functions.
- Refined by [0012](0012-backfiller-registry.md): backfillers lease only for their capabilities.
- Refined by [0014](0014-live-coverage.md): live coverage.
- Refined by [0019](0019-measured-publish-delay.md): measured publish delay.
- Refined by [0020](0020-backfill-runs-are-internal.md): backfill runs are internal.

## Context

Backtests and strategies need history. ADR 0009 made each backfill run its own
writer, but it did not say who starts a run and for which data. A command line
per run is hard to automate and invisible to other services. We want all
control in SQL, so that a dashboard can become the user interface later.

History sources usually deliver data in fixed units, for example one file per
day. Users think in arbitrary periods.

## Decision

### Desired state: history requests

A user, a dashboard or another service declares the history it needs:

```sql
select api.add_history_request(instrument_id, 'trade', from, to, publish_delay);
```

Each call adds a row to `market.history_request`: one instrument, one stream,
one period of any length, and the publish delay for the rows (ADR 0009). The
call sends `NOTIFY empiriq_history`. Requests only add. Removing history is a
separate, explicit action (by writer or by period), because other requests,
frames and backtests can use the same rows.

### Observed state: coverage

A backfill run now covers one period (`market.backfill_run.period`). The
**coverage** of a stream is the union of the periods of all successful runs.
`api.history_coverage` shows, for each stream, the requested, covered and
missing periods as multiranges:

```
missing = union(requested periods) − union(covered periods)
```

### Reconciliation: leases

A backfiller is a long-running service. It listens on `empiriq_history`, and
it also checks regularly. To get work, it calls `api.lease_history()`. The
function:

1. finds the first missing period of any request that no running run covers
   and that did not fail recently;
2. cuts it to at most `max_span`;
3. starts a backfill run for it (a new writer) and returns the run.

The backfiller loads only rows inside the leased period and ends the run with
`api.finish_backfill()`. On success the period becomes covered. On failure it
becomes missing again, and it can be leased again after `retry_after`.

A lease is not permanent. A run that is still `running` after `stale_after` is
marked `abandoned` and its writer is closed. The period then becomes missing
again. Leases are taken one after the other, so several backfillers never
lease the same period.

### Units of the source are a detail of the backfiller

The database works with arbitrary periods only. A backfiller maps a leased
period to the units of its source, for example the daily files that overlap
the period, and filters the rows to the period.

### A role for control actions

`empiriq_operator` can call control functions such as `api.add_history_request()`
and `api.set_strategy_status()`. A dashboard with controls uses this role. The
role `empiriq_reader` stays read-only.

> ADR 0011 replaces this section: `empiriq_operator` is the only role for
> people and the console, and `empiriq_reader` is removed.

## Consequences

- One SQL call starts a backfill. A repeated request costs nothing, because
  covered periods are not loaded again.
- Several backfillers share the work without coordination.
- Progress and errors are visible in SQL, so a dashboard can show them.
- Coverage counts only backfill runs. Live data of feeders does not count, so
  a request over a live period loads it again. The primary keys make this
  safe, but it costs time.
- A source that cannot deliver a period (for example, the data does not exist
  yet) produces failed runs every `retry_after`. Failed runs stay in the log.
- Backfillers become long-running services, like feeders.
