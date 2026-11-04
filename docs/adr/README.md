# Architecture Decision Records

An ADR is a short record of a decision that shapes the project. It states the
decision, the reasons and the costs. To change a decision, add a new ADR that
replaces the old one. Do not change the decision in an accepted ADR.

Format: `NNNN-title.md` with *Status*, optional *Related*, and the sections
*Context*, *Decision* and *Consequences*.

- **Status** is `Proposed`, `Accepted`, or `Superseded by` with a
  link. A proposed ADR can still change. Only the project owner sets
  `Accepted`.
- **Related** is a list of links to other ADRs:
  - `Supersedes` / `Refines`: the older ADRs that this ADR changes, with
    their titles.
  - `Refined by`: the newer ADRs that change this one, with one short phrase
    about the change.
- If a new ADR refines or supersedes an older one, update both ADRs in the
  same pull request.
- Exception: [0000](0000-empiriq.md) is the root and links to no other ADR.
  An ADR without another parent refines 0000, but 0000 gets no `Refined by`.
  So details never change the root. The table [By part](#by-part) is the
  way down from the root.

## By part

An ADR can belong to more than one part. If a new ADR is added, add it here
too.

| Part | ADRs |
|------|------|
| Whole project | [0000](0000-empiriq.md) |
| Integration through the database | [0001](0001-database-is-the-api.md) |
| Desired state and observed state | [0002](0002-desired-vs-observed.md), [0004](0004-intent-levels.md), [0017](0017-venue-trading-rules.md) |
| Frames and determinism | [0005](0005-logical-time.md), [0008](0008-frames.md), [0009](0009-writers.md) |
| `database`: strategies and engine | [0006](0006-strategies-are-stable-functions.md), [0013](0013-strategies-build-bars.md), [0015](0015-strategies-run-as-strategy-role.md) |
| `database`: market data | [0007](0007-market-data-model.md), [0009](0009-writers.md), [0014](0014-live-coverage.md), [0019](0019-measured-publish-delay.md) |
| `feeder` | [0014](0014-live-coverage.md), [0016](0016-live-requests.md), [0018](0018-services-report-instruments.md), [0021](0021-services-identified-by-name.md) |
| `backfiller` | [0010](0010-declarative-history.md), [0012](0012-backfiller-registry.md), [0018](0018-services-report-instruments.md), [0019](0019-measured-publish-delay.md), [0020](0020-backfill-runs-are-internal.md), [0021](0021-services-identified-by-name.md) |
| `reconciler` | [0002](0002-desired-vs-observed.md), [0008](0008-frames.md), [0017](0017-venue-trading-rules.md) |
| `console` | [0011](0011-console.md), [0020](0020-backfill-runs-are-internal.md) |

## All ADRs

| #    | Decision                                                                                                                | Status             |
|------|-------------------------------------------------------------------------------------------------------------------------|--------------------|
| 0000 | [empiriq: a trading platform around PostgreSQL, built from services](0000-empiriq.md)                                   | Proposed           |
| 0001 | [The database is the API](0001-database-is-the-api.md)                                                                  | Proposed           |
| 0002 | [Separate desired state and observed state](0002-desired-vs-observed.md)                                                | Proposed           |
| 0003 | [Cycle barrier with NOTIFY and a trigger](0003-cycle-barrier.md)                                                        | Superseded by 0008 |
| 0004 | [Three intent levels compiled to orders](0004-intent-levels.md)                                                         | Proposed           |
| 0005 | [Logical time](0005-logical-time.md)                                                                                    | Proposed           |
| 0006 | [Strategies are STABLE SQL functions](0006-strategies-are-stable-functions.md)                                          | Proposed           |
| 0007 | [Market data: raw trades and depth snapshots, all other values derived](0007-market-data-model.md)                      | Proposed           |
| 0008 | [Frames: reconciliation drives strategies, cutoffs make them deterministic](0008-frames.md)                             | Proposed           |
| 0009 | [Writers: cutoffs for each writer](0009-writers.md)                                                                     | Proposed           |
| 0010 | [Declarative history: requests, coverage and leases](0010-declarative-history.md)                                       | Proposed           |
| 0011 | [Console: one user interface, only through `api`](0011-console.md)                                                      | Proposed           |
| 0012 | [Backfiller registry: services report, work is controlled](0012-backfiller-registry.md)                                 | Proposed           |
| 0013 | [Strategies build bars, the console gets trade buckets](0013-strategies-build-bars.md)                                  | Proposed           |
| 0014 | [Live coverage: feeders report continuous periods](0014-live-coverage.md)                                               | Proposed           |
| 0015 | [Strategies run as `empiriq_strategy` and read market data only through `api`](0015-strategies-run-as-strategy-role.md) | Proposed           |
| 0016 | [Live requests: feeders register, operators declare live streams](0016-live-requests.md)                                | Proposed           |
| 0017 | [empiriq does not store the trading rules of venues](0017-venue-trading-rules.md)                                       | Proposed           |
| 0018 | [Feeders and backfillers report instruments](0018-services-report-instruments.md)                                       | Proposed           |
| 0019 | [The publish delay is measured from live trades](0019-measured-publish-delay.md)                                        | Proposed           |
| 0020 | [Backfill runs are internal](0020-backfill-runs-are-internal.md)                                                        | Proposed           |
| 0021 | [Feeders and backfillers are identified by their name](0021-services-identified-by-name.md)                             | Proposed           |
