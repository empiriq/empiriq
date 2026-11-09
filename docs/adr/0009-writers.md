# 0009. Writers: cutoffs for each writer

**Status:** Proposed

**Related:**

- Refines [0008. Frames: reconciliation drives strategies, cutoffs make them deterministic](0008-frames.md).

## Context

ADR 0008 records one cutoff for each stream: the highest `seq` at the end of
a frame. This is correct only if `seq` grows in commit order. So it allowed
only one writer for each stream.

A backfiller breaks this rule: it writes into the same streams as the live
feeder. Example:

1. The backfiller inserts a row and gets `seq` 100, but does not commit yet.
2. The feeder commits a row with `seq` 101.
3. A frame records the cutoff 101.
4. The backfiller commits `seq` 100.

A replay of the frame now sees row 100, but the live frame did not.

## Options

- **A lock for each stream.** Writers wait for each other. A long backfill
  blocks the live feeder.
- **Cutoffs by MVCC snapshot.** Exact, but transaction ids are internal to
  PostgreSQL. They do not survive a logical dump.
- **Cutoffs by receive time with a safety margin.** No margin is safe for
  every delay.
- **One central sequencer.** One more component, and all writers depend on
  it.
- **A cutoff for each writer.** Each writer keeps `seq` in commit order for
  itself. A frame records one cutoff for each stream and writer.

## Decision

- A writer is one process that writes market data: a feeder or a backfiller.
  A service has one writer for its whole life. It gets the same writer back
  after a restart.
- Every market row records its writer.
- A writer commits its transactions for one instrument one after the other.
  Then `seq` grows in commit order for each writer and instrument.
- A frame records a cutoff for each stream and each writer with rows in it. A
  frame sees a row only if its `seq` is not above the cutoff of its writer.
  Rows of a writer without a cutoff are invisible.
- The writer is a plain column, not a partition key. Otherwise the same trade
  could exist once for each writer.

## Consequences

- Feeders and backfillers do not block each other.
- No locks and no transaction ids. Logical dumps work.
- Each row shows the service that wrote it.
- The number of cutoffs in a frame depends on the number of services, not on
  the size of the history.
- Two processes with the same writer break the commit order. Run one process
  for each writer.
- A writer loads several instruments in parallel, but one period of one
  instrument at a time.
