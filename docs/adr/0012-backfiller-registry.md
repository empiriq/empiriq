# 0012. Backfiller registry: services report, work is controlled

**Status:** Proposed

**Related:**

- Refines [0010. Declarative history: requests, coverage and leases](0010-declarative-history.md).
- Refined by [0016](0016-live-requests.md): feeders register the same way.
- Refined by [0018](0018-services-report-instruments.md): services report instruments.
- Refined by [0021](0021-services-identified-by-name.md): backfillers are identified by their name.

## Context

With ADR 0010, every backfiller can lease every missing period. A second
backfiller for another venue would lease periods that it cannot load, and
its runs would fail. `leased_by` is free text, so nobody can see which
backfillers run, what they can do, or whether they are alive.

There are two ways to add control:

- **Desired state for services.** An operator sets a backfiller to `paused`
  or `running`, and empiriq reconciles it, like Kubernetes reconciles pods.
- **Desired state for work.** Services only report about themselves. An
  operator controls the work: history requests, later subscriptions and
  accounts.

## Decision

### Services have no desired state

empiriq does not start, stop, scale or pause services. Their life cycle
belongs to `docker compose`, Kubernetes or a person. The database keeps only
what services report: observed state.

### Backfillers register and send heartbeats

- A backfiller calls `api.register_backfiller(name, source, venues, streams,
  version, settings)` when it starts. It works like "apply" and returns the id
  of the backfiller.
- Its **capabilities** are all pairs of the venues and streams
  (`market.backfiller_stream`).
- Its **settings** are its lease settings: `max_span`, `retry_after`,
  `stale_after` and `heartbeat_interval`. The backfiller declares them once,
  not with every lease.
- It calls `api.backfiller_heartbeat(id)` every `heartbeat_interval`, also
  while a long run loads. `api.lease_history(id)` counts as a heartbeat too.
- `api.backfiller` shows each backfiller, whether it is alive (a heartbeat
  within 3 intervals), its running runs and its results of the last 24 hours.

### Leases match capabilities

`api.lease_history(backfiller_id)` gives only periods of active requests
whose venue and stream are capabilities of the backfiller. Each backfill run
records its backfiller (`backfill_run.backfiller_id`), not a text.

A running run is abandoned when its backfiller sent no heartbeat for
`stale_after`. Before, a run was abandoned when it was older than
`stale_after`, even if its backfiller still worked on it.

### Work has desired state

A history request has `desired_status`: `active` or `paused`.
`api.pause_history_request(id)` stops new runs for the request. Running runs
finish. `api.resume_history_request(id)` makes it active again and wakes the
backfillers.

## Consequences

- Several backfillers for different venues work side by side.
- The console shows backfillers like any other resource: alive or not, with
  their runs, without code for it.
- To stop a backfiller, stop its container. Its runs are abandoned after
  `stale_after` and leased again.
- A backfiller that stops without deregistering stays in the registry and
  shows as not alive. A row of a dead backfiller is harmless. Cleanup of old
  rows is TBD.
- Feeders and reconcilers can follow the same pattern later: they register
  and report, and only their work is controlled.
- `api.lease_history()` and `api.start_backfill()` have new signatures. This
  is a breaking change of `api`.
