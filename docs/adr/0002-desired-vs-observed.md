# 0002. Separate desired state and observed state

**Status:** Proposed

**Related:**

- Refines [0000. empiriq: a trading platform around PostgreSQL, built from services](0000-empiriq.md).

## Context

If strategies write orders directly, there is no difference between "what we
want" and "what the venue has". Then every failure enters the strategy code:
timeouts, partial fills, rejections and restarts.

## Decision

- Only reconcilers write observed state (`balance`, `position`, `order`,
  `fill`). They use `api.report_*`.
- Only the engine writes desired state (`desired_balance`, `desired_position`,
  `desired_order`). It uses the strategy output.
- `api.order_action` lists the order actions (`place`, `cancel`, `resolve`)
  that reconcilers must do to reconcile.
- The identity of an order is a deterministic `client_order_id`. It comes from
  the strategy, the order key and the revision.

## Consequences

- Strategies are pure functions. Reconcilers are generic and make no decisions.
- After a crash, a reconciler reads the order actions again. Retries are
  idempotent.
- A decision reaches the venue in the next pass of the reconciler, not in
  the same transaction.
- Observed orders follow an enforced state machine. It has an explicit
  `unknown` status.
