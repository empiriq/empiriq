# 0000. empiriq: a trading platform around PostgreSQL, built from services

**Status:** Proposed

This ADR is the root of all other ADRs. It states what empiriq is and which
parts it has. It links to no other ADR: the other ADRs refine it. The
[index](README.md#by-part) lists the ADRs of each part.

## Context

empiriq is an algorithmic trading platform. It must support:

- decisions within one second or faster: market making, intraday strategies
  and portfolio rebalancing;
- backtests and research with the same code as live trading;
- many venues, with code for each venue written by different people, often in
  different languages;
- a history of market data that grows while you trade.

High-frequency trading (HFT), where microseconds decide, is not a goal.

## Decision

### The database is the center

PostgreSQL holds the market data, the state of every account and the
strategies. All other parts are services that talk only to the database,
never to each other. The public contract is the `api` schema.

Two ideas shape the whole design:

- **Desired state and observed state.** Strategies say what they want. The
  venue says what is true. Reconcilers make the two match.
- **Determinism.** A strategy sees only its frame: logical time `as_of` and
  the cutoffs of its streams. So a frame gives the same result in a replay
  and in a backtest.

### Services

empiriq has five services. Each service has its own repository and runs in
its own container.

| Service      | Does                                                                                                                             |
|--------------|----------------------------------------------------------------------------------------------------------------------------------|
| `database`   | PostgreSQL with the schema, the engine and `api`. Runs strategies in frames and plans orders.                                    |
| `feeder`     | Writes live market data from one venue or data source, for the active live requests.                                             |
| `backfiller` | Loads requested history in backfill runs.                                                                                        |
| `reconciler` | Reconciles accounts with one venue, reports observed state and starts frames.                                                    |
| `console`    | The user interface: shows the state and changes desired state, only through `api`. Built with Grafana; its pages are dashboards. |

### Control is declarative

An operator changes desired state through `api`: strategies, live requests
and history requests. Services report about themselves and do the work that
matches their capabilities. empiriq does not start or stop services.

## Consequences

- You can write a service in any language that has a PostgreSQL driver.
- A new venue needs a feeder, a backfiller and a reconciler. The database
  and the console do not change.
- The database adds latency compared with an in-process engine. The latency
  budget of a frame is in milliseconds.
- `api` is the contract of every service, so it needs strict versioning.
- A new ADR that adds a service or changes the role of one also updates the
  table of services here.

The [architecture](../explanation/architecture.md) explains the design in
detail. The [glossary](../reference/glossary.md) defines the terms.
