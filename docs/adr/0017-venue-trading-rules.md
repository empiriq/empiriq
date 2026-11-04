# 0017. empiriq does not store the trading rules of venues

**Status:** Proposed

**Related:**

- Refines [0004. Three intent kinds planned into orders](0004-intent-kinds.md).

## Context

`core.instrument` had `tick_size`, `lot_size` and `min_notional`: the price
step, the quantity step and the minimum notional of an order. An operator
entered them by hand. The planner rounded quantities down to the lot size.
Nothing else used them, and nothing kept them up to date when the venue
changed them.

The venue knows its trading rules and checks every order. A copy in empiriq
can only be older than the original.

## Decision

- `core.instrument`, `api.instrument` and `api.register_instrument()` have
  no trading rules.
- The planner does not round. It sends `abs(delta)` if it is larger than the
  tolerance.
- If the venue rejects an order, the reconciler reports the status
  `rejected` with the message of the venue in `status_reason`
  (`api.report_order()`). The operator sees it in `api."order"`.
- A strategy that needs a price or quantity step rounds itself, for example
  with a param. The example `strategies.grid` has the param `price_step`.

## Consequences

- An instrument has only what it is: venue, symbol, kind and assets. There is
  nothing to keep in sync with the venue.
- More orders can be rejected. The planner makes a new order in every
  reconciliation while the delta stays above the tolerance. So a quantity
  that the venue always rejects is sent again and again. Strategies must set
  a tolerance of at least one step of the venue. A planner that stops after
  a rejection is TBD.
- Small differences, for example from fees in the base asset, become orders
  if the tolerance is 0.
- A backtest does not check trading rules. A simulated reconciler accepts
  orders that the venue would reject.
