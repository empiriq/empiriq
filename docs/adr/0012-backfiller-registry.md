# 0012. Backfiller registry: services report, work is controlled

**Status:** Proposed

**Related:**

- Refines [0010. Declarative history: history requests, coverage and backfill runs](0010-declarative-history.md).
- Refined by [0016](0016-live-requests.md): feeders register the same way.
- Refined by [0018](0018-services-report-instruments.md): services report instruments.

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

- A backfiller calls `api.register_backfiller(name, venues, streams,
  version, settings)` when it starts. It works like "apply" on the name.
- The **name** identifies the backfiller in all later calls: it is the
  primary key of `market.backfiller`. Another name is another process. A
  service takes its name from an environment variable; its image sets a
  default, so a second process of the same image needs only another value.
- Its **capabilities** are all pairs of the venues and streams
  (`market.backfiller_stream`).
- Its **settings** apply to `api.lease_history()`: `max_span`, `retry_after`,
  `stale_after` and `heartbeat_interval`. The backfiller declares them once,
  not with every call.
- It calls `api.backfiller_heartbeat(name)` every `heartbeat_interval`, also
  while a long run loads. `api.lease_history(name)` counts as a heartbeat too.
- `api.backfiller` shows each backfiller, whether it is alive (a heartbeat
  within 3 intervals), its running runs and its results of the last 24 hours.

### Backfillers lease only what they can load

`api.lease_history(name)` gives only periods of active requests whose venue
and stream are capabilities of the backfiller. Each backfill run records the
name of its backfiller (`backfill_run.backfiller_name`), or null if it was
started by hand.

A running run is abandoned when its backfiller sent no heartbeat for
`stale_after`, not when it is older than `stale_after`. So a long run of a
working backfiller is not abandoned.

### Work has desired state

A history request has `desired_status`: `active` or `paused`.
`api.pause_history_request(id)` stops new runs for the request. Running runs
finish. `api.resume_history_request(id)` makes it active again and wakes the
backfillers.

## Consequences

- Several backfillers for different venues work side by side.
- The board shows backfillers like any other resource, by name: alive or
  not, with their runs, without code for it.
- To stop a backfiller, stop its container. Its runs are abandoned after
  `stale_after` and leased again.
- A backfiller that stops without deregistering stays in the registry and
  shows as not alive. A row of a dead backfiller is harmless. Cleanup of old
  rows is TBD.
- Feeders follow the same pattern ([ADR 0016](0016-live-requests.md)).
  Reconcilers can follow it later.
