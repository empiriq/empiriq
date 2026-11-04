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

| #    | Decision                                                                  | Status   |
|------|---------------------------------------------------------------------------|----------|
| 0001 | [The database is the API](0001-database-is-the-api.md)                    | Proposed |
| 0002 | [Separate desired state and observed state](0002-desired-vs-observed.md)  | Proposed |
| 0003 | [Cycle barrier with NOTIFY and a trigger](0003-cycle-barrier.md)          | Superseded by 0008 |
| 0004 | [Three intent levels compiled to orders](0004-intent-levels.md)           | Proposed |
| 0005 | [Logical time](0005-logical-time.md)                                      | Proposed |
| 0006 | [Strategies are STABLE SQL functions](0006-strategies-are-stable-functions.md) | Proposed |
| 0007 | [Market data: raw trades and depth snapshots, all other values derived](0007-market-data-model.md) | Proposed |
| 0008 | [Frames: reconciliation drives strategies, cutoffs make them deterministic](0008-frames.md) | Proposed |
| 0009 | [Writers: cutoffs for each writer](0009-writers.md) | Proposed |
| 0010 | [Declarative history: requests, coverage and leases](0010-declarative-history.md) | Proposed |
| 0011 | [Console: one user interface, only through `api`](0011-console.md) | Proposed |
| 0012 | [Backfiller registry: services report, work is controlled](0012-backfiller-registry.md) | Proposed |
| 0013 | [Strategies build bars, the console gets trade buckets](0013-strategies-build-bars.md) | Proposed |
| 0014 | [Live coverage: feeders report continuous periods](0014-live-coverage.md) | Proposed |
| 0015 | [Strategies run as `empiriq_strategy` and read market data only through `api`](0015-strategies-run-as-strategy-role.md) | Proposed |
| 0016 | [Live requests: feeders register, operators declare live streams](0016-live-requests.md) | Proposed |
| 0017 | [empiriq does not store the trading rules of venues](0017-venue-trading-rules.md) | Proposed |
| 0018 | [Feeders and backfillers report instruments](0018-services-report-instruments.md) | Proposed |
| 0019 | [The publish delay is measured from live trades](0019-measured-publish-delay.md) | Proposed |
| 0020 | [Backfill runs are internal](0020-backfill-runs-are-internal.md) | Proposed |
| 0021 | [Feeders and backfillers are identified by their name](0021-services-identified-by-name.md) | Proposed |
