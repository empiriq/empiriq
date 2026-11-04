# 0015. Strategies run as `empiriq_strategy` and read market data only through `api`

**Status:** Proposed

**Related:**

- Refines [0006. Strategies are STABLE SQL functions](0006-strategies-are-stable-functions.md).
- Refines [0008. Frames: reconciliation drives strategies, cutoffs make them deterministic](0008-frames.md).

## Context

Frames run inside `api.reconciled()`. This function is `SECURITY DEFINER`,
so it runs as the owner of the schema. A strategy is called from there, and
so it had all privileges of the owner.

Also, `empiriq_strategy` had `select` on all tables of `market`. Both allowed
a strategy to read `market.trade` or `market.depth` directly. Such a read
ignores `event_time <= as_of` and the cutoffs of the frame. A replay of the
frame can then see other rows, and nothing shows that the strategy broke the
rule.

## Decision

- `core.call_strategy(entrypoint, ctx)` calls the entrypoint. The function is
  `SECURITY DEFINER` and owned by `empiriq_strategy`. So the strategy, and
  every invoker function that it calls, runs as `empiriq_strategy`.
- `empiriq_strategy` has no `select` on tables of `market`. It keeps `usage`
  on the schema for its types.
- Strategies read market data only through the helper functions
  `api.trades()`, `api.last_quote()` and `api.depth_levels()`. These
  functions apply the visibility rules. They are `SECURITY DEFINER` with a
  fixed `search_path`.
- `api.register_strategy()` refuses a `SECURITY DEFINER` entrypoint. Such a
  function would run with the privileges of its owner.

## Consequences

- A frame of a strategy depends only on what the frame rules let it see. A
  direct read of `market.*` fails with `permission denied`, and the frame has
  the status `error`.
- A strategy can read only what `empiriq_strategy` can read: the views
  `api.balance`, `api.position`, `api."order"`, `api.instrument` and the
  helper functions. These views still show all accounts. Filtering them by
  the account of the frame is TBD.
- The operator can call `api.last_quote()` and `api.depth_levels()` too.
  Before, they failed, because the operator has no `select` on `market`.
- `SECURITY DEFINER` functions are not inlined by the planner. A strategy that
  joins `api.trades()` is not optimized across the call. For the current
  helper functions this costs little.
- This answers the open question "Strategy privileges" of the architecture
  without a separate runner. A runner is still an option for timeouts and
  for frames in their own transactions.
