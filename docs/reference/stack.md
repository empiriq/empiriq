# The stack

> Reference for the stack of the `empiriq` repository: services, images,
> settings, the environment of the containers, logs and make targets. To run
> and change the stack, see [Run the stack](../how-to/run-the-stack.md).

The stack runs all services of empiriq together on one computer, for trying
and testing. `docker-bake.hcl` builds the images, `compose.yaml` runs them.
The stack mounts nothing of the service repositories: every change goes
through an image.

## Services

| Service              | Repository             | What it does |
|----------------------|------------------------|--------------|
| `database`           | `database`             | PostgreSQL 18 with the empiriq migrations in the image |
| `binance-backfiller` | `binance-backfiller`   | Loads history from the Binance archive |
| `binance-feeder`     | `binance-feeder`       | Writes live trades of the live requests from the Binance WebSocket streams |
| `board`              | `board`                | Grafana with the board |
| `log-store`          | `empiriq`              | Loki: stores the logs of all containers (`etc/log-store.yaml`) |
| `log-agent`          | `empiriq`              | Alloy: sends the output of all containers to the log store (`etc/log-agent.alloy`) |

Each service repository has one `Dockerfile` and builds one image. It knows
nothing about the `empiriq` repository or about compose.

## Images

All images have the tag `dev`. The stack is for trying and testing, so the
images have no versions. Each image has the git revision of its repository
in the label `org.opencontainers.image.revision`.

| Image                            | Built from |
|----------------------------------|------------|
| `empiriq/database:dev`           | the stage `database` of the Dockerfile of `database` |
| `empiriq/binance-backfiller:dev` | the Dockerfile of `binance-backfiller` |
| `empiriq/binance-feeder:dev`     | the Dockerfile of `binance-feeder` |
| `empiriq/board:dev`              | the Dockerfile of `board` |

`IMAGE_SOURCE` decides where `make build` takes the code:

- `local` (default): the repositories next to the `empiriq` repository, as
  they are on disk. `*_DIR` sets their paths.
- `git`: the default branch on GitHub, in the organization
  `IMAGE_GITHUB_ORG`. No clones are needed.

There is no registry yet. Compose never pulls the images
(`IMAGE_PULL_POLICY=never`). With a registry, set `IMAGE_REGISTRY` (for
example `ghcr.io/<org>/`) and `IMAGE_PULL_POLICY=missing`.

## Settings

`.env` has the settings of the stack. `.env.example` has all of them with
their defaults; `compose.yaml` has no defaults of its own. `make` creates
`.env` from `.env.example` if it is missing.

The names follow one rule: `IMAGE_*` for all images, `<SERVICE>_*` for one
service, with its name in `compose.yaml`.

| Variable                 | Default                | Meaning |
|--------------------------|------------------------|---------|
| `IMAGE_SOURCE`           | `local`                | Where `make build` takes the code: `local` or `git` |
| `IMAGE_GITHUB_ORG`       | `empiriq`              | The GitHub organization for `IMAGE_SOURCE=git` |
| `IMAGE_REGISTRY`         | empty                  | Prefix of the image names. Empty: local images. |
| `IMAGE_PULL_POLICY`      | `never`                | `never`: only local images. `missing`: pull from `IMAGE_REGISTRY`. |
| `DATABASE_DIR`, `BINANCE_BACKFILLER_DIR`, `BINANCE_FEEDER_DIR`, `BOARD_DIR` | `../<repository>` | Paths of the repositories for `IMAGE_SOURCE=local` |
| `DATABASE_PORT`          | `1792`                 | Port of the database on this computer |
| `DATABASE_NAME`          | `empiriq`              | The database |
| `DATABASE_USER`          | `empiriq`              | Its owner. All services log in as this owner. |
| `DATABASE_PASSWORD`      | `empiriq`              | Password of the owner |
| `BOARD_PORT`             | `3000`                 | Port of the board on this computer. Only `127.0.0.1`: the board has no login for operators. |
| `BOARD_ADMIN_USER`       | `admin`                | The first Grafana user |
| `BOARD_ADMIN_PASSWORD`   | `admin`                | Its password. Grafana uses it only when it starts the first time. |
| `LOG_STORE_RETENTION`    | `168h`                 | How long the log store keeps the logs |

## Environment of the containers

The containers take their environment only from the files in `env/`
(`env_file` in `compose.yaml`). The files map the settings of `.env` to the
names that the images expect: `${DATABASE_NAME}` takes the value of `.env`.

| File                       | Services | Variables |
|----------------------------|----------|-----------|
| `env/database.env`         | `database` | `POSTGRES_*` of the image: the database and its owner |
| `env/database-client.env`  | all services that use the database | The [database connection](#database-connection) |
| `env/binance-backfiller.env` | `binance-backfiller` | `BACKFILLER_*`, `EMPIRIQ_DATABASE_ROLE` |
| `env/binance-feeder.env`   | `binance-feeder` | `FEEDER_*`, `EMPIRIQ_DATABASE_ROLE` |
| `env/board.env`            | `board` | `GF_*` of Grafana, `EMPIRIQ_LOG_STORE_URL` |
| `env/log-store.env`        | `log-store` | `LOKI_RETENTION` |

**Local files.** Each service also reads `env/<service>.local.env` if it
exists. Git ignores these files. A variable of the local file wins over the
same variable of the other files.

**Secrets from files.** The database image reads `POSTGRES_PASSWORD_FILE`
instead of `POSTGRES_PASSWORD`. Grafana reads `GF_<SECTION>_<KEY>__FILE`
instead of `GF_<SECTION>_<KEY>`. Mount the file with `secrets:` in
`compose.override.yaml`.

## Database connection

All services connect to the database with the same variables.

| Variable                    | Meaning |
|-----------------------------|---------|
| `EMPIRIQ_DATABASE_HOST`     | Host of the empiriq database |
| `EMPIRIQ_DATABASE_PORT`     | Its port |
| `EMPIRIQ_DATABASE_NAME`     | The database |
| `EMPIRIQ_DATABASE_USER`     | Login |
| `EMPIRIQ_DATABASE_PASSWORD` | Its password |
| `EMPIRIQ_DATABASE_ROLE`     | Optional, feeders and backfillers only: the role to switch to at connect |

In the stack, all services log in as the owner of the database. Feeders and
backfillers switch to the role `empiriq_feeder` at connect. So they have
only the privileges of that role, and a missing grant in `api` shows at
once. This is a check, not protection: a service could reset the role.
Grafana cannot set a role at connect, so the board works as the owner. It
still changes data only through `api` functions
([ADR 0011](../adr/0011-board.md)).

## Logs

The log agent reads the output of every container of the stack through the
Docker socket and sends it to the log store. The board shows the logs on the
page **Logs**.

| Label       | Value |
|-------------|-------|
| `service`   | The service in `compose.yaml` |
| `container` | The name of the container |
| `level`     | `info`, `warn` or `error`. Lines without a level have no `level`. |

The log agent takes the level from JSON (`"level":"INFO"`, the Go services),
from logfmt (`level=info`, Grafana) and from PostgreSQL (`LOG:`, `WARNING:`,
`ERROR:`, `FATAL:`). PostgreSQL writes `DETAIL`, `HINT` and `STATEMENT` of an
error as separate lines; the log agent joins them with the error into one
entry.

The services keep the default log driver of Docker. If the log store or the
log agent stops, the services do not notice, and `make logs` still works.

## Make targets

`make help` lists all targets. `S=<service>` limits a target to one service
or image.

| Target         | Does |
|----------------|------|
| `build`        | Builds all images, or one with `S` |
| `up`           | Starts the database and, when it is healthy, all services. Uses only built images. |
| `rebuild`      | Builds the images again and restarts the services whose image changed. Says so if the image of the database changed. |
| `restart`      | Restarts all services, or one with `S`, with the current images |
| `down`         | Stops all services. The data stays. |
| `reset`        | Deletes all data: the database, the settings and users of Grafana. Then starts everything again. |
| `status`, `logs` | Shows the containers, follows the logs |
| `open`         | Opens the board in the browser |
| `lint`, `check-docs` | Checks `compose.yaml` and `docker-bake.hcl`; checks the docs against the code |

These targets run psql in the database container as the owner.

| Target         | Shows or does |
|----------------|---------------|
| `psql`         | An interactive psql |
| `sql`          | Runs a SQL file: `make sql F=examples/03-strategy.sql` |
| `strategies`, `worlds`, `frames`, `strategy-runs` | Strategies, worlds, the last 20 frames and strategy runs |
| `writers`, `streams` | Writers of market data, coverage of all streams |
| `coverage`, `runs`, `backfillers`, `delays` | History: coverage, the last 20 backfill runs, backfillers, measured publish delays |
| `feeders`, `live` | Feeders, live requests with their lag |
| `live-request` | Requests live data: `make live-request VENUE=binance-spot SYMBOL=BTCUSDT STREAMS=trade,depth` |
| `instrument`   | Registers an instrument: `make instrument VENUE=binance-spot SYMBOL=BTCUSDT KIND=spot` |
| `request`      | Requests history: `make request SYMBOL=BTCUSDT FROM=2026-06-01 TO=2026-06-02` |
| `check`        | Checks loaded trades: `make check SYMBOL=BTCUSDT FROM=2026-06-01 TO=2026-06-02` |

## Files

```
compose.yaml        the services, from the images
docker-bake.hcl     how the images are built: sources and tags
.env.example        the settings and their defaults
env/                the environment of each container
etc/log-store.yaml  Loki: storage and retention of logs
etc/log-agent.alloy Alloy: which logs go to the log store, and their labels
examples/           SQL for the tutorial
Makefile            make targets
```
