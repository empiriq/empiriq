# 0016. Live requests: feeders register, operators declare live streams

**Status:** Proposed

**Related:**

- Refines [0012. Backfiller registry: services report, work is controlled](0012-backfiller-registry.md).
- Refines [0014. Live coverage: feeders report continuous periods](0014-live-coverage.md).
- Refined by [0018](0018-services-report-instruments.md): feeders report instruments.

## Context

History is declarative: an operator adds a history request, and backfillers
load what is missing ([ADR 0010](0010-declarative-history.md)). Live data had
no such model. A feeder would write the streams of its configuration, and a
change needs a restart. Nobody sees which feeders run, what they can write,
or whether they are alive.

ADR 0012 says that services report about themselves, and that only their
work has desired state. Live data is work too.

## Decision

### Feeders register and send heartbeats

- A feeder calls `api.register_feeder(name, venues, streams, version,
  settings)` when it starts. It works like "apply" on the name, like a
  backfiller ([ADR 0012](0012-backfiller-registry.md)). It returns the id of
  its writer. The writer has the same name as the feeder, and every row of
  market data stores the writer id.
- Its **capabilities** are all pairs of the venues and streams
  (`market.feeder_stream`), like those of a backfiller.
- `api.feeder` shows each feeder: whether it is alive (a heartbeat within 3
  heartbeat intervals), its capabilities and the number of streams it writes.

### Live requests are the desired state of live data

- A live request is one stream of one instrument: `market.live_request`, at
  most one for each stream.
- An operator calls `api.add_live_request()`, `api.pause_live_request()`,
  `api.resume_live_request()` and `api.withdraw_live_request()`. Each call
  sends `NOTIFY empiriq_live`.
- `api.live_streams(name)` returns the active live requests that match
  the capabilities of the feeder. The call counts as a heartbeat.
- A feeder calls it at start, after each notification and regularly. It
  subscribes to new streams at the venue, and unsubscribes from streams that
  are no longer returned.

### Every capable feeder writes

Live requests are not leased. Every alive feeder with the capability writes
the stream. Two feeders for the same venue write the same rows. The primary
keys make this safe, and the stream stays covered if one feeder stops.

## Consequences

- The board shows feeders and live requests like any other resource, with
  forms to add, pause, resume and withdraw.
- A paused or withdrawn request stops its live coverage. The time until it
  is resumed is an uncovered period.
- A feeder needs no list of symbols in its configuration. It needs only its
  venues.
- There is no balancing between feeders: each writes all streams it can. A
  limit for each feeder is TBD.
- A feeder writes with one writer for its whole life. Frames record cutoffs
  for that writer, as for any writer ([ADR 0009](0009-writers.md)).
