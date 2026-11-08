# Environment of the containers

compose.yaml passes these files to the containers (`env_file`). Each
service takes the files it needs, so the same setting comes from one place.
`${VAR}` takes a value of `.env` (see `.env.example`).

| File                     | Service            | What |
|--------------------------|--------------------|------|
| `database.env`           | database           | The database and its owner (`POSTGRES_*` of the image) |
| `database-client.env`    | all that use the database | The connection to the database (`EMPIRIQ_DATABASE_*`) |
| `binance-backfiller.env` | binance-backfiller | `BACKFILLER_*`, the role at connect |
| `binance-feeder.env`     | binance-feeder     | `FEEDER_*`, the role at connect |
| `board.env`              | board              | `GF_*` (Grafana) and `EMPIRIQ_LOG_STORE_URL` |
| `log-store.env`          | log-store          | Loki |

log-agent has no settings of its own, only a local file.

**Local additions.** Each service also reads `<service>.local.env` if it
exists. Git ignores these files. Put there what only your installation
needs, for example any setting of Grafana as `GF_<SECTION>_<KEY>` in
`board.local.env`. A variable of the local file wins over the same variable
of the files above.

**Secrets.** Images that support it read a secret from a file: `X_FILE`
instead of `X` for the database image (`POSTGRES_PASSWORD_FILE`), and
`GF_<SECTION>_<KEY>__FILE` for Grafana. Mount the file with `secrets:` in
`compose.override.yaml`.
