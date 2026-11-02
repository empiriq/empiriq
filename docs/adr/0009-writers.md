# 0009. Writers: cutoffs for each writer

**Status:** Proposed. Refines [0008](0008-frames.md). Refined by [0010](0010-declarative-history.md): who starts backfill runs.

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

### Every row has a writer

- `market.writer` registers each process that writes market data. A feeder
  calls `api.register_writer(name)` once at start. Each backfill run is its own
  writer, created by `api.start_backfill()`.
- Every row of `market.trade` and `market.depth` has a `writer_id`.
- One rule remains: a writer commits its transactions for one instrument one
  after the other. Then `seq` grows in commit order for each writer and
  instrument.

### Cutoffs for each open writer

At frame start, the engine records a cutoff for each subscribed stream and each
**open** writer: the highest `seq` of that writer, or 0 if it has no rows yet.

### Closed writers

A writer that has finished calls `api.close_writer()`. A backfill run does this
in `api.finish_backfill()`. Closing gives the writer a `close_order`. Closes get
their `close_order` one after the other. Each frame records the highest
`close_order` at its start as `close_horizon`.

A frame sees a row if `event_time <= as_of` and one of these is true for its
writer:

- the writer was open at frame start, and `seq <=` its cutoff;
- the writer was closed before frame start: `close_order <= close_horizon`.

A writer that did not exist at frame start has neither. Its rows are invisible
to that frame and to its replay. A closed writer cannot write again (a trigger
rejects the insert).

So frames record cutoffs only for open writers: usually the feeders and at
most a few running backfills. Old backfill runs cost nothing.

### Not physical partitions

`writer_id` is a plain column with the index `(instrument_id, writer_id, seq)`.
Partitions by writer would force `writer_id` into the primary key. Then the
same trade could exist once for each writer, and bars would count it twice.

## Consequences

- Feeders and backfillers do not block each other. A backfiller can work on
  several instruments in parallel.
- No locks, no `xid` columns, no snapshots. Logical dumps work, because `seq`
  and `writer_id` are plain data.
- Each row has its origin. A bad backfill run can be removed with
  `delete ... where writer_id = ...`.
- Each frame stores one cutoff for each subscribed stream and open writer.
- A trigger checks on each insert that the writer is open. This costs one
  index lookup for each row.
