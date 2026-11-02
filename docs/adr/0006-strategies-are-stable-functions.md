# 0006. Strategies are STABLE SQL functions

**Status:** Proposed. Refined by [0015](0015-strategies-run-as-strategy-role.md): strategies run as `empiriq_strategy`.

## Context

"Strategies in SQL" needs an exact contract. It must define the input, the
output and what a strategy can do.

## Decision

- Signature: `(api.strategy_context) returns setof api.intent`.
  `api.register_strategy()` checks it.
- The function must be `STABLE` or `IMMUTABLE`. Then PostgreSQL rejects
  statements that change data inside it.
- Strategy state between frames uses the intent `api.set_state(jsonb)`. The
  next frame receives it as `ctx.state`.
- The implementation language is free: SQL, PL/pgSQL or any other language
  that supports `STABLE`.
- Each run is a subtransaction. If a run fails, the previous desired state
  stays. `api.frame` shows the error.

## Consequences

- Strategies are deterministic functions of their inputs. They are easy to
  test, for example with pgTAP.
- `STABLE` is a strong convention, not a sandbox. A `STABLE` function can call
  a `VOLATILE` function. Real isolation needs one role for each strategy (open
  question).
