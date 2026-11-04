# 0009. Writers: cutoffs for each writer

**Status:** Proposed

**Related:**

- Refines [0008. Frames: reconciliation drives strategies, cutoffs make them deterministic](0008-frames.md).
- Refined by [0010](0010-declarative-history.md): who starts backfill runs.

## Context

ADR 0008 records one cutoff for each stream: the highest `seq` at frame start.
This is correct only if `seq` grows in commit order, so it required exactly one
writer for each stream.

A backfiller breaks this rule: it writes into the same streams as the live
feeder. Example:

1. The backfiller inserts a row and gets `seq` 100, but does not commit yet.
2. The feeder commits a row with `seq` 101.
3. A frame records the cutoff 101.
4. The backfiller commits `seq` 100.

A replay of the frame now sees row 100, but the live frame did not. We
considered four options: a lock for each stream, cutoffs by MVCC snapshot
(`xid`), cutoffs by `received_at` with a safety margin, and one central
sequencer.

## Decision

### A writer is a service process

- `market.writer` has one writer for each process that writes market data:
  each feeder and each backfiller. Its `kind` is `feeder` or `backfiller`. A
  service is its writer: it registers with `api.register_writer()`, which
  returns the id. The same name returns the same writer after a restart.
- The writer also keeps what the service reports: version, settings,
  heartbeat and capabilities (`market.writer_stream`). There is no separate
  table for feeders or backfillers ([ADR 0012](0012-backfiller-registry.md),
  [ADR 0016](0016-live-requests.md)).
- Every row of `market.trade` and `market.depth` has a `writer_id`.
- One rule remains: a writer commits its transactions for one instrument one
  after the other. Then `seq` grows in commit order for each writer and
  instrument. So a backfiller loads several instruments in parallel, but one
  period of one instrument at a time.

### Cutoffs for each writer

When a frame ends, before its strategies run, the engine records a cutoff for
each subscribed stream and each writer with rows in it: the highest `seq` of
that writer. A frame sees a
row if `event_time <= as_of` and `seq <=` the cutoff of its writer.

A writer without rows at that moment has no cutoff. Its rows are invisible to
that frame and to its replay.

### Writers do not close

A writer lives as long as its service. A finished backfill run needs no
step of its own: its rows are below the cutoffs of all later frames.

### Not physical partitions

`writer_id` is a plain column with the index `(instrument_id, writer_id, seq)`.
Partitions by writer would force `writer_id` into the primary key. Then the
same trade could exist once for each writer, and bars would count it twice.

## Consequences

- Feeders and backfillers do not block each other.
- No locks, no `xid` columns, no snapshots. Logical dumps work, because `seq`
  and `writer_id` are plain data.
- Each row has its origin: the service that wrote it. The rows of one
  backfill run are the rows of its writer in the instrument, stream and
  period of the run.
- The number of cutoffs in a frame depends on the number of services, not on
  the history: one for each subscribed stream and writer with rows in it.
- Inserts need no check of the writer. Two processes with the same name write
  with one writer and break the commit order. Run one process for each name.
- The writer of a service that is gone stays. Its cutoffs do not change any
  more.
