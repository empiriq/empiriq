# stack

The empiriq stack: the database and all services, started together with
Docker Compose. Use it on your computer for development, and as the start
for a server.

| Service              | Repository             | What it does |
|----------------------|------------------------|--------------|
| `empiriq`            | `../empiriq`           | PostgreSQL 18 with the empiriq migrations, on localhost:1792 |
| `logins`             | this repository        | Creates the database logins of the services, then exits |
| `binance-backfiller` | `../binance-backfiller`| Loads history from the Binance archive |
| `binance-feeder`     | `../binance-feeder`    | Writes live trades of the live requests from the Binance WebSocket streams |
| `console`            | `../console`           | Grafana with the console, on localhost:3000 |

The services are built from the repositories next to this one:

```
empiriq/
  empiriq/              database: migrations, api, docs
  binance-backfiller/
  binance-feeder/
  console/
  stack/                this repository
```

## Run

```sh
make up       # build and start everything
make open     # the console: http://localhost:3000, admin / admin
make help     # all targets
```

`make up` starts the database first. When it is healthy, `logins` creates the
logins. Then the services start.

- `make logs S=console` follows the logs of one service.
- `make restart S=binance-backfiller` rebuilds and restarts one service, for
  example after a change of its code.
- `make reset` deletes all data: the database and the settings and users of
  Grafana. Then it starts everything again. The migrations run on the new
  database.

## Database logins

Each service logs in with its own login. Each login is a member of one role
of empiriq, so it can do only what that role allows
([empiriq ADR 0001](../empiriq/docs/adr/0001-database-is-the-api.md)).

| Login                | Role               | Used by |
|----------------------|--------------------|---------|
| `empiriq_console`    | `empiriq_operator` | console |
| `empiriq_backfiller` | `empiriq_feeder`   | binance-backfiller |
| `empiriq_binance_feeder` | `empiriq_feeder` | binance-feeder |
| `empiriq`            | owner              | migrations, `make psql` and the targets below |

`sql/logins.sql` creates the logins and sets their passwords. It runs on
every `make up`, so the logins come back after `make reset`. The logins live
in the database: there is no other step after a reset.

## Settings

Copy `.env.example` to `.env` and change what you need. All values in
`.env.example` are the defaults.

- **Paths:** `EMPIRIQ_DIR`, `BINANCE_BACKFILLER_DIR`, `BINANCE_FEEDER_DIR`, `CONSOLE_DIR`.
- **Database:** `EMPIRIQ_PG_PORT`, `EMPIRIQ_DB_NAME`, and the owner login
  `EMPIRIQ_DB_ADMIN_USER` / `EMPIRIQ_DB_ADMIN_PASSWORD`.
- **Logins of the services:** `CONSOLE_DB_*`, `BACKFILLER_DB_*`, `FEEDER_DB_*`.
- **Console:** `CONSOLE_PORT`, `CONSOLE_ADMIN_USER`, `CONSOLE_ADMIN_PASSWORD`.
  The admin password is used only when Grafana starts the first time.

Change the passwords before you run the stack on a server.

## Work with the data

These targets run psql in the database container as the owner.

| Target        | Shows or does |
|---------------|---------------|
| `psql`        | an interactive psql |
| `strategies`, `frames` | strategies and the last 20 frames |
| `writers`, `streams`   | writers of market data, coverage of all streams |
| `coverage`, `runs`, `backfillers` | history: coverage, last 20 backfill runs, backfillers |
| `feeders`, `live` | feeders, live requests with their lag |
| `live-request` | requests live data: `make live-request VENUE=binance-spot SYMBOL=BTCUSDT` |
| `instrument`  | registers an instrument: `make instrument VENUE=binance-spot SYMBOL=BTCUSDT KIND=spot` |
| `request`     | requests history: `make request SYMBOL=BTCUSDT FROM=2026-06-01 TO=2026-06-02` |
| `check`       | checks loaded trades: `make check SYMBOL=BTCUSDT FROM=2026-06-01 TO=2026-06-02` |

## Development

- **Console.** The stack mounts `dashboards/` and `grafana/provisioning/` of
  the console repository. Grafana reads changed dashboards within 10 seconds.
  No rebuild is needed.
- **Database.** A change of the migrations needs `make reset`. The migrations
  run only on an empty database.
- **A new service.** Add it to `compose.yaml`. If it needs a database login,
  add the login to `sql/logins.sql` and to `.env.example`.

Tests of the services do not use the stack. `make smoke` in empiriq and
`make test-integration` in binance-backfiller start their own database in
memory.

## Layout

```
compose.yaml      the database, logins and services
sql/logins.sql    database logins of the services
.env.example      settings and their defaults
Makefile          make targets
```
