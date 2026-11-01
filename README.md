# stack

The empiriq stack: the database and all services, started together with
Docker Compose. Use it on your computer for development, and as the start
for a server.

| Service              | Repository             | What it does |
|----------------------|------------------------|--------------|
| `empiriq`            | `../empiriq`           | PostgreSQL 18 with the empiriq migrations, on localhost:1792 |
| `binance-backfiller` | `../binance-backfiller`| Loads history from the Binance archive |
| `binance-feeder`     | `../binance-feeder`    | Writes live trades of the live requests from the Binance WebSocket streams |
| `console`            | `../console`           | Grafana with the console, on localhost:3000 |
| `loki`               | this repository        | Stores the logs of all containers (`loki/config.yaml`) |
| `alloy`              | this repository        | Reads the output of all containers and sends it to Loki (`alloy/config.alloy`) |

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

`make up` starts the database first. When it is healthy, the services start.

- `make logs S=console` follows the logs of one service.
- `make restart S=binance-backfiller` rebuilds and restarts one service, for
  example after a change of its code.
- `make reset` deletes all data: the database and the settings and users of
  Grafana. Then it starts everything again. The migrations run on the new
  database.

## Database login

All services log in as the owner of the database: `POSTGRES_USER` and
`POSTGRES_PASSWORD`. The stack is for development, so one login is enough.

Feeders and backfillers switch to the role `empiriq_feeder` at connect:

```
postgres://empiriq:empiriq@empiriq:5432/empiriq?options=-c%20role%3Dempiriq_feeder
```

So they have only the privileges of that role, and a missing grant in `api`
shows at once. This is a check, not protection: a service could reset the
role. Grafana cannot set a role at connect, so the console works as the
owner. It still changes data only through `api` functions.

### Logins on a real installation

Create a login for each service and give it its role
([empiriq ADR 0001](../empiriq/docs/adr/0001-database-is-the-api.md)):

```sql
create role binance_feeder login password '...' in role empiriq_feeder;
create role console login password '...' in role empiriq_operator;
```

Then use these logins in the database URLs of the services, without
`options`.

## Logs

Alloy reads the output of every container of the project through the Docker
socket and sends it to Loki. The console shows it on the page **Logs**:
filters for the service, the level and a text search.

- **Labels:** `service` (the compose service), `container` and `level`.
  Alloy takes the level from JSON (`"level":"INFO"`, the Go services) and
  from logfmt (`level=info`, Grafana). Lines without a level have no `level`.
- **Retention:** `LOKI_RETENTION`, default 7 days (`168h`).
- **No dependency:** the services keep the default log driver. If Loki or
  Alloy stops, the services do not notice, and `make logs` still works.

## Settings

Copy `.env.example` to `.env` and change what you need. All values in
`.env.example` are the defaults.

- **Paths:** `EMPIRIQ_DIR`, `BINANCE_BACKFILLER_DIR`, `BINANCE_FEEDER_DIR`, `CONSOLE_DIR`.
- **Database:** `EMPIRIQ_PG_PORT`, `POSTGRES_DB`, and the owner login
  `POSTGRES_USER` / `POSTGRES_PASSWORD`, used by all services.
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
| `coverage`, `runs`, `backfillers`, `delays` | history: coverage, last 20 backfill runs, backfillers, measured publish delays |
| `feeders`, `live` | feeders, live requests with their lag |
| `live-request` | requests live data: `make live-request VENUE=binance-spot SYMBOL=BTCUSDT STREAMS=trade,depth` |
| `instrument`  | registers an instrument for a venue without a service (services report theirs): `make instrument VENUE=binance-spot SYMBOL=BTCUSDT KIND=spot` |
| `request`     | requests history: `make request SYMBOL=BTCUSDT FROM=2026-06-01 TO=2026-06-02` |
| `check`       | checks loaded trades: `make check SYMBOL=BTCUSDT FROM=2026-06-01 TO=2026-06-02` |

## Development

- **Console.** The stack mounts `dashboards/` and `grafana/provisioning/` of
  the console repository. Grafana reads changed dashboards within 10 seconds.
  No rebuild is needed.
- **Database.** A change of the migrations needs `make reset`. The migrations
  run only on an empty database.
- **A new service.** Add it to `compose.yaml`. A new feeder or backfiller
  uses the owner login with `options=-c%20role%3Dempiriq_feeder`.

Tests of the services do not use the stack. `make smoke` in empiriq and
`make test-integration` in binance-backfiller start their own database in
memory.

## Layout

```
compose.yaml        the database and the services
loki/config.yaml    Loki: storage and retention of logs
alloy/config.alloy  Alloy: which logs go to Loki, and their labels
.env.example        settings and their defaults
Makefile            make targets
```
