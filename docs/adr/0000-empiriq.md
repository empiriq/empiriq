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
strategies. All other services talk only to the database, never to each
other. The public contract is the `api` schema.

Two ideas shape the whole design:

- **Desired state and observed state.** Strategies say what they want. The
  venue says what is true. Reconcilers make the two match.
- **Determinism.** A strategy sees only its frame: logical time `as_of` and
  the cutoffs of its streams. So a frame gives the same result in a replay
  and in a backtest.

### The database and the kinds of services

The `database` is a special service: the shared database of empiriq. It is
PostgreSQL with the schema, the engine and `api`. It runs strategies in
frames and plans orders.

Every other service has one of four kinds:

| Kind       | Does                                                                                 |
|------------|--------------------------------------------------------------------------------------|
| feeder     | Writes live market data from a venue or a data source, for the active live requests. |
| backfiller | Loads requested history in backfill runs.                                            |
| reconciler | Reconciles accounts with a venue, reports observed state and starts frames.          |
| other      | Any other work. It uses only `api` and has no contract of its own.                   |

A feeder, a backfiller and a reconciler each follow a contract with the
database: how they register, report and get their work. Each service has its
own repository, for example `binance-feeder`, and runs in its own container.

### Control is declarative

An operator changes desired state through `api`: strategies, live requests
and history requests. Services report about themselves and do the work that
matches their capabilities. empiriq does not start or stop services.

## Consequences

- You can write a service in any language that has a PostgreSQL driver.
- A new venue needs a feeder, a backfiller and a reconciler. The database
  and the auxiliary services do not change.
- The database adds latency compared with an in-process engine. The latency
  budget of a frame is in milliseconds.
- `api` is the contract of every service, so it needs strict versioning.
- A new ADR that adds a kind of service or changes the role of one also
  updates the table of kinds here.

The [architecture](../explanation/architecture.md) explains the design in
detail. The [glossary](../reference/glossary.md) defines the terms.
