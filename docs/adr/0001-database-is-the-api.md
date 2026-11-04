# 0001. The database is the API

**Status:** Proposed

**Related:**

- Refines [0000. empiriq: a trading platform around PostgreSQL, built from services](0000-empiriq.md).

## Context

Each venue needs its own feeder and reconciler. Different people will write
them, often in different languages. They need a stable integration point.

An RPC service in front of the database is one option. But it would copy the
state model, and it would add one more component.

## Decision

- PostgreSQL is the only integration point.
- The public contract is the `api` schema, plus the `market` tables for bulk
  loading. All other schemas are internal.
- All write operations use `SECURITY DEFINER` functions with a fixed
  `search_path`.
- `LISTEN/NOTIFY` coordinates the processes.

## Consequences

- You can write a feeder or a reconciler in any language that has a PostgreSQL
  driver.
- The `api` schema needs strict versioning: semantic versions, deprecations
  and a generated reference.
- The number of connections grows with the number of services. Later we may
  need a connection pooler. For `LISTEN`, PgBouncer must use session mode.
- We can refactor the internal tables freely. The views and functions in
  `api` hide them.
