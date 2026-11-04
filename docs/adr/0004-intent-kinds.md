# 0004. Three intent kinds planned into orders

**Status:** Proposed

**Related:**

- Refines [0000. empiriq: a trading platform around PostgreSQL, built from services](0000-empiriq.md).
- Refined by [0017](0017-venue-trading-rules.md): the planner does not round to the lot size.

## Context

Different strategies think in different units:

- a rebalancer thinks in balances;
- a trend follower thinks in positions;
- a market maker thinks in orders.

The platform must accept all three. Reconcilers must not need to understand all
three.

## Decision

- Strategies send `api.intent` rows of the kind `balance`, `position`,
  `order` or `state`.
- **Full replace:** the output of a run is the **complete** desired state of
  the strategy. The strategy no longer manages assets and instruments that are
  missing from the output.
- Planners (`core.plan`) convert balance and position targets into desired
  orders with `origin = 'planner'`. They use
  `delta = target − observed − in-flight` and a tolerance. They do not round
  to the trading rules of the venue ([ADR 0017](0017-venue-trading-rules.md)).
- Claims: in one account, only one strategy with one intent kind can target an
  asset or instrument. A run with a conflict fails.
- If an order intent with the same key changes, its revision increments. The
  new revision gives a new `client_order_id`. The reconciler sees this as
  `cancel` and `place`.

## Consequences

- Reconcilers only work with orders.
- Planner logic is replaceable SQL. Execution quality can improve without
  changes to strategies.
- "Send no intent" and "close the position" are different. Docs and examples
  must make this clear.
- Conversion supports only direct spot pairs for now.
