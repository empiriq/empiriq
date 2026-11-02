# 0021. Feeders and backfillers are identified by their name

**Status:** Proposed. Refines [0012](0012-backfiller-registry.md) and
[0016](0016-live-requests.md).

## Context

A feeder and a backfiller had three fields that say who they are: `id`,
`name` and `source`.

- `name` already identified the process: registration works like "apply"
  on the name.
- `id` was a second key for the same thing. A service had to keep it from
  the registration and pass it to every call.
- `source` was free text that nothing read. The name and the capabilities
  already say where the data comes from.

## Decision

- The name is the primary key of `market.feeder` and `market.backfiller`.
  The columns `id` and `source` are removed. Another name is another
  process.
- References use the name: `feeder_stream.feeder_name`,
  `backfiller_stream.backfiller_name` and `backfill_run.backfiller_name`.
- The functions of feeders and backfillers take the name:
  `api.live_streams(name)`, `api.feeder_heartbeat(name)`,
  `api.backfiller_heartbeat(name)` and `api.lease_history(name)`.
- `api.register_feeder()` returns only the id of the writer.
  `api.register_backfiller()` returns nothing. Neither takes a source.
- `api.start_backfill()` takes no source. A run stores the name of its
  backfiller, or null if it was started by hand.
- A service takes its name from an environment variable. Its image sets a
  default, so a second process of the same image needs only another value.

## Consequences

- A service needs no state from the registration except the id of its writer
  (feeders only). The writer keeps a numeric id, because every row of market
  data stores it.
- The console shows feeders and backfillers by name, also in the address of
  the record page.
- This is a breaking change of `api`. Feeders and backfillers change in the
  same release.
