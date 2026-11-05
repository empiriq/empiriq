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

- A backfiller calls `api.register_writer(name, 'backfiller', venues,
  streams, version, settings)` when it starts. It works like "apply" on the
  name and returns the id of its writer ([ADR 0009](0009-writers.md)). A
  backfiller is a writer of the kind `backfiller`: `market.writer` keeps its
  registration.
- The **name** identifies the backfiller in all later calls: it is unique
  in `market.writer`. Another name is another process. A
  service takes its name from an environment variable; its image sets a
  default, so a second process of the same image needs only another value.
- Its **capabilities** are all pairs of the venues and streams
  (`market.writer_stream`). New venues and venue streams
  (`core.venue_stream`) are registered with them
  ([ADR 0018](0018-services-report-instruments.md)).
- Its **settings** apply to `api.take_work()`: `max_span`, `retry_after`,
  `stale_after` and `heartbeat_interval`. The backfiller declares them once,
  not with every call.
- It calls `api.writer_heartbeat(name)` every `heartbeat_interval`, also
  while a long run loads. `api.take_work(name)` counts as a heartbeat too.
- `api.writer` shows each writer: its kind, whether it is alive (a heartbeat
  within 3 intervals) and its capabilities.

### Backfillers lease only what they can load

`api.take_work(name)` gives a backfiller at most one period of active
requests whose venue and stream are capabilities of the backfiller. Each
backfill run records the writer of its backfiller (`backfill_run.writer_id`).

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
- The board shows backfillers with the feeders on the page Writers, by
  name: alive or not, without code for it.
- To stop a backfiller, stop its container. Its runs are abandoned after
  `stale_after` and leased again.
- A backfiller that stops without deregistering stays in the registry and
  shows as not alive. A row of a dead backfiller is harmless. Cleanup of old
  rows is TBD.
- Feeders follow the same pattern with the same functions
  ([ADR 0016](0016-live-requests.md)).
  Reconcilers can follow it later.
