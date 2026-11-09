# Architecture Decision Records

An ADR records one decision: which options we considered, which one we chose,
and what it costs. It answers **why**. It does not describe how empiriq works
now.

## Where to find what

| Question | Document | Changes |
|----------|----------|---------|
| How does it work now? | [Explanation](../explanation/) and [reference](../reference/) | With every change of the code, in place |
| Why does it work this way? | ADRs | Never, after `Accepted` |

- Edit the living documents in place. Never add a document that only
  corrects another one.
- Each living document ends with a list of the ADRs of its part.
- Code changes often, decisions rarely. So names of tables, columns and
  functions belong into the living documents. An ADR uses the terms of the
  [glossary](../reference/glossary.md).

## When to write an ADR

Write an ADR only if you chose between options. If a change has no real
alternative, update the living documents and write no ADR.

A pull request that changes a decision does three things:

1. It changes the code.
2. It updates the living documents to the new state.
3. It adds a new ADR. If the new ADR replaces an old one, the old one gets
   the status `Superseded by`.

## Format

`NNNN-title.md` with *Status*, optional *Related*, and the sections
*Context*, *Options*, *Decision* and *Consequences*. Keep it short.

- **Status** is `Proposed`, `Accepted`, or `Superseded by` with a link.
  - A proposed ADR is still under discussion. You can change it.
  - Only the project owner sets `Accepted`. An accepted ADR does not change.
  - A superseded ADR stays. It records an approach that was tried and why it
    was left.
- **Related** links only to older ADRs: `Supersedes` or `Refines`, with
  their titles. A new ADR never changes an older one. The only exception is
  the status `Superseded by`.
- Exception: [0000](0000-empiriq.md) is the root and links to no other ADR.
  An ADR without another parent refines 0000. The table [By part](#by-part)
  is the way down from the root.

Some ADRs still use the old format: they have `Refined by` links and names
from the code. They move to the new format part by part, together with a
living document for that part.

## By part

An ADR can belong to more than one part. If a new ADR is added, add it here
too.

| Part | How it works | ADRs |
|------|--------------|------|
| Whole project | [Architecture](../explanation/architecture.md) | [0000](0000-empiriq.md) |
| Integration through the database | [Architecture](../explanation/architecture.md#big-picture) | [0001](0001-database-is-the-api.md) |
| Desired state and observed state | [Architecture](../explanation/architecture.md#desired-state-and-observed-state) | [0002](0002-desired-vs-observed.md), [0004](0004-intent-kinds.md), [0017](0017-venue-trading-rules.md) |
| Frames and determinism | [Frames, time and determinism](../explanation/frames.md) | [0005](0005-logical-time.md), [0008](0008-frames.md), [0009](0009-writers.md), [0022](0022-frames-belong-to-a-world.md) |
| `database`: strategies and engine | [Architecture](../explanation/architecture.md#writing-a-strategy) | [0006](0006-strategies-are-stable-functions.md), [0013](0013-strategies-build-bars.md), [0015](0015-strategies-run-as-strategy-role.md) |
| `database`: market data | [Market data](../reference/market-data.md) | [0007](0007-market-data-model.md), [0009](0009-writers.md), [0014](0014-live-coverage.md), [0019](0019-measured-publish-delay.md) |
| `feeder` | [Architecture](../explanation/architecture.md#writing-a-feeder-a-backfiller-or-a-reconciler) | [0014](0014-live-coverage.md), [0016](0016-live-requests.md), [0018](0018-services-report-instruments.md) |
| `backfiller` | [Architecture](../explanation/architecture.md#writing-a-feeder-a-backfiller-or-a-reconciler) | [0010](0010-declarative-history.md), [0012](0012-backfiller-registry.md), [0018](0018-services-report-instruments.md), [0019](0019-measured-publish-delay.md) |
| `reconciler` | [Architecture](../explanation/architecture.md#writing-a-feeder-a-backfiller-or-a-reconciler) | [0002](0002-desired-vs-observed.md), [0008](0008-frames.md), [0017](0017-venue-trading-rules.md), [0022](0022-frames-belong-to-a-world.md) |
| `board` | [Architecture](../explanation/architecture.md#services) | [0011](0011-board.md) |

## All ADRs

| #    | Decision                                                                                                                | Status             |
|------|-------------------------------------------------------------------------------------------------------------------------|--------------------|
| 0000 | [empiriq: a trading platform around PostgreSQL, built from services](0000-empiriq.md)                                   | Proposed           |
| 0001 | [The database is the API](0001-database-is-the-api.md)                                                                  | Proposed           |
| 0002 | [Separate desired state and observed state](0002-desired-vs-observed.md)                                                | Proposed           |
| 0003 | [Cycle barrier with NOTIFY and a trigger](0003-cycle-barrier.md)                                                        | Superseded by 0008 |
| 0004 | [Three intent kinds planned into orders](0004-intent-kinds.md)                                                          | Proposed           |
| 0005 | [Logical time](0005-logical-time.md)                                                                                    | Proposed           |
| 0006 | [Strategies are STABLE SQL functions](0006-strategies-are-stable-functions.md)                                          | Proposed           |
| 0007 | [Market data: raw trades and depth snapshots, all other values derived](0007-market-data-model.md)                      | Proposed           |
| 0008 | [Frames: reconciliation drives strategies, cutoffs make them deterministic](0008-frames.md)                             | Proposed           |
| 0009 | [Writers: cutoffs for each writer](0009-writers.md)                                                                     | Proposed           |
| 0010 | [Declarative history: history requests, coverage and backfill runs](0010-declarative-history.md)                        | Proposed           |
| 0011 | [Board: one user interface, only through `api`](0011-board.md)                                                          | Proposed           |
| 0012 | [Backfiller registry: services report, work is controlled](0012-backfiller-registry.md)                                 | Proposed           |
| 0013 | [Strategies build bars, the board gets trade buckets](0013-strategies-build-bars.md)                                    | Proposed           |
| 0014 | [Live coverage: feeders report continuous periods](0014-live-coverage.md)                                               | Proposed           |
| 0015 | [Strategies run as `empiriq_strategy` and read market data only through `api`](0015-strategies-run-as-strategy-role.md) | Proposed           |
| 0016 | [Live requests: feeders register, operators declare live streams](0016-live-requests.md)                                | Proposed           |
| 0017 | [empiriq does not store the trading rules of venues](0017-venue-trading-rules.md)                                       | Proposed           |
| 0018 | [Feeders and backfillers report instruments](0018-services-report-instruments.md)                                       | Proposed           |
| 0019 | [The publish delay is measured from live trades](0019-measured-publish-delay.md)                                        | Proposed           |
| 0022 | [Frames belong to a world, the last reconciler closes a frame](0022-frames-belong-to-a-world.md)                        | Proposed           |
