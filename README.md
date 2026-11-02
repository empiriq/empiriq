# empiriq

**Algorithmic trading where the database is the platform.**

empiriq keeps the current state of your trading accounts in PostgreSQL:
balances, positions and orders. It also keeps the market data for these
accounts. Strategies are SQL functions. They declare what the account
*should* look like. Small external services load market data and reconcile the
account with the venue.

```sql
-- A strategy is a function from context to desired state.
create function strategies.sma_cross(ctx api.strategy_context)
  returns setof api.intent language sql stable as $$
  with bar as (   -- closes of the last 30 closed 1-minute bars
    select (array_agg(price order by trade_time desc))[1] as close,
           row_number() over (order by date_bin('1 minute', trade_time, '2000-01-01Z') desc) as rn
      from api.trades(2, date_bin('1 minute', ctx.as_of, '2000-01-01Z') - interval '30 minutes',
                      date_bin('1 minute', ctx.as_of, '2000-01-01Z'), ctx.as_of)
     group by date_bin('1 minute', trade_time, '2000-01-01Z'))
  select api.target_position(2, case when fast > slow then 1 else 0 end)
    from (select avg(close) filter (where rn <= 10) as fast, avg(close) as slow from bar) ma
$$;
```

> **Status: early draft.** The schema and the API will change. Do not use it
> with real money yet.

## Ideas

- **Declarative.** You state *what* you want: a balance, a position or a set
  of orders. Planners and reconcilers decide *how*.
- **Desired state and observed state.** Strategies never call the venue.
  Reconcilers make the account match the desired state, like Kubernetes
  controllers.
- **One code path for backtests and live trading.** Strategies use the logical
  time `as_of`, never `now()`.
- **The database is the API.** You can write feeders and reconcilers in any
  language that has a PostgreSQL driver.

## Quickstart

Clone the repositories of empiriq next to each other (see
[Repositories](#repositories)). Then, in this repository:

```sh
make build && make up     # build the images and start everything
make open                 # the console: http://localhost:3000

make sql F=examples/01-live-trades.sql   # live trades of BTCUSDT
make sql F=examples/02-history.sql       # the trades of yesterday
make sql F=examples/03-strategy.sql      # a strategy on the history of yesterday
```

The tutorial [Your first strategy](docs/tutorials/first-strategy.md) explains
each step.

## Documentation

- [Your first strategy](docs/tutorials/first-strategy.md): a tutorial of 15
  minutes. Start here.
- [Architecture](docs/explanation/architecture.md): how and why empiriq works.
- [Glossary](docs/reference/glossary.md): the terms of the project.
- [Market data](docs/reference/market-data.md): the contract for feeders and
  backfillers.
- [ADRs](docs/adr/README.md): the decisions and their reasons.
- [docs/README.md](docs/README.md) lists all documents and the rules for
  writing them.

## Repositories

This repository builds the images of all repositories and runs them
together: `docker buildx bake` builds, Docker Compose runs. Use it on your
computer for development, or to try empiriq without cloning every repository.

| Service              | Repository             | What it does |
|----------------------|------------------------|--------------|
| `empiriq`            | `../database`          | PostgreSQL 18 with the empiriq migrations in the image, on localhost:1792 |
| `binance-backfiller` | `../binance-backfiller`| Loads history from the Binance archive |
| `binance-feeder`     | `../binance-feeder`    | Writes live trades of the live requests from the Binance WebSocket streams |
| `console`            | `../console`           | Grafana with the console, on localhost:3000 |
| `loki`               | this repository        | Stores the logs of all containers (`loki/config.yaml`) |
| `alloy`              | this repository        | Reads the output of all containers and sends it to Loki (`alloy/config.alloy`) |

Each service repository has one `Dockerfile` and builds one image. It knows
nothing about this repository or about compose.

## Run

```sh
make build    # build the images of all repositories
make up       # start the database and all services
make rebuild  # after a change of the repositories: build again, restart what changed
make open     # the console: http://localhost:3000, no login
make help     # all targets
```

`make up` starts the database first. When it is healthy, the services start.
It uses only built images: if one is missing, it says so.

- `make logs S=console` follows the logs of one service.
- `make rebuild` builds all images again and restarts the services whose
  image changed. Docker builds again only what changed. If the image of the
  database changed, `make rebuild` says so: the new migrations run only after
  `make reset`, which deletes all data.
- `make rebuild S=binance-feeder` builds one image again and restarts its
  service, for example after a change of its code.
- `make reset` deletes all data: the database and the settings and users of
  Grafana. Then it starts everything again. The migrations of the image run on
  the new database.

## Images

`docker-bake.hcl` describes the images. `make build` runs `docker buildx
bake` with the variables of `versions.env` and `.env`.

| Image | Repository | Target |
|-------|------------|--------|
| `empiriq/empiriq:<version>` | empiriq | the stage `empiriq` of its Dockerfile |
| `empiriq/binance-backfiller:<version>` | binance-backfiller | the Dockerfile |
| `empiriq/binance-feeder:<version>` | binance-feeder | the Dockerfile |
| `empiriq/console:<version>` | console | the Dockerfile |

**Sources.** `SOURCE` decides where bake takes the code:

- `local` (default): the repositories next to this one, as they are on disk,
  with the version `dev`:
  ```
  empiriq/
    database/
    binance-backfiller/
    binance-feeder/
    console/
    empiriq/              this repository
  ```
- `git`: GitHub, at the tags of `versions.env`. No clones are needed:
  ```sh
  make build SOURCE=git                    # the organization empiriq
  make build SOURCE=git GITHUB_ORG=<org>   # a fork
  ```

**Versions.** `versions.env` has one version for each image. A set of tags
that work together is a release of the stack. Each image reports its version
in its labels (`org.opencontainers.image.version`, `revision`), and the Go
services also in their registration.

**Registry.** There is none yet. The images are local, and compose never
pulls them (`PULL_POLICY=never`). With a registry, set `REGISTRY` (for
example `ghcr.io/<org>/`) and `PULL_POLICY=missing`. GitHub Actions can use the
same `docker-bake.hcl` and only add the push.

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
([empiriq ADR 0001](docs/adr/0001-database-is-the-api.md)):

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
  Alloy takes the level from JSON (`"level":"INFO"`, the Go services), from
  logfmt (`level=info`, Grafana) and from Postgres (`LOG:`, `WARNING:`,
  `ERROR:`, `FATAL:`, as `info`, `warn` and `error`). Postgres writes the
  `DETAIL`, `HINT` and `STATEMENT` of an error as separate lines: Alloy joins
  them with the error into one entry. Lines without a level have no `level`.
- **Retention:** `LOKI_RETENTION`, default 7 days (`168h`).
- **No dependency:** the services keep the default log driver. If Loki or
  Alloy stops, the services do not notice, and `make logs` still works.

## Settings

Copy `.env.example` to `.env` and change what you need. All values in
`.env.example` are the defaults.

- **Images:** `SOURCE`, `GITHUB_ORG`, `REGISTRY`, `PULL_POLICY`. The versions
  are in `versions.env`.
- **Paths for `SOURCE=local`:** `DATABASE_DIR`, `BINANCE_BACKFILLER_DIR`,
  `BINANCE_FEEDER_DIR`, `CONSOLE_DIR`.
- **Database:** `EMPIRIQ_PG_PORT`, `POSTGRES_DB`, and the owner login
  `POSTGRES_USER` / `POSTGRES_PASSWORD`, used by all services.
- **Console:** `CONSOLE_PORT`, `CONSOLE_ADMIN_USER`, `CONSOLE_ADMIN_PASSWORD`.
  The admin password is used only when Grafana starts the first time. The
  console has no login for operators, so it listens on `127.0.0.1` only.

Change the passwords before you run the stack on a server.

## Work with the data

These targets run psql in the database container as the owner.

| Target        | Shows or does |
|---------------|---------------|
| `psql`        | an interactive psql |
| `sql`         | runs a SQL file: `make sql F=examples/03-strategy.sql` |
| `strategies`, `frames` | strategies and the last 20 frames |
| `writers`, `streams`   | writers of market data, coverage of all streams |
| `coverage`, `runs`, `backfillers`, `delays` | history: coverage, last 20 backfill runs, backfillers, measured publish delays |
| `feeders`, `live` | feeders, live requests with their lag |
| `live-request` | requests live data: `make live-request VENUE=binance-spot SYMBOL=BTCUSDT STREAMS=trade,depth` |
| `instrument`  | registers an instrument for a venue without a service (services report theirs): `make instrument VENUE=binance-spot SYMBOL=BTCUSDT KIND=spot` |
| `request`     | requests history: `make request SYMBOL=BTCUSDT FROM=2026-06-01 TO=2026-06-02` |
| `check`       | checks loaded trades: `make check SYMBOL=BTCUSDT FROM=2026-06-01 TO=2026-06-02` |

## Development

The stack mounts nothing of the service repositories. Every change goes
through an image:

- **Code of a service:** `make build S=<service> && make restart S=<service>`.
- **Dashboards of the console:** the same with `S=console`. It takes seconds:
  the image only copies the files.
- **Migrations:** `make build S=empiriq && make reset`. The migrations run only
  on an empty database.
- **A new service.** Add it to `compose.yaml`. A new feeder or backfiller
  uses the owner login with `options=-c%20role%3Dempiriq_feeder`.

Tests of the services do not use the stack. `make smoke` in database builds
and runs its own test image. `make test-integration` in binance-backfiller and
binance-feeder starts the image `empiriq/empiriq:dev` with the data in
memory.

## Layout

```
docs/               the documentation of the project
examples/           SQL for the tutorial: live trades, history, a strategy
docker-bake.hcl     how the images are built: sources, versions, tags
versions.env        the version of each image
compose.yaml        the database and the services, from the images
loki/config.yaml    Loki: storage and retention of logs
alloy/config.alloy  Alloy: which logs go to Loki, and their labels
.env.example        settings and their defaults
Makefile            make targets
```
