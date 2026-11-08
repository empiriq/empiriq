# Run the stack

> How to start the stack of empiriq on your computer and change it. The
> [stack reference](../reference/stack.md) lists all settings and targets.

You need Docker with Compose, `make` and `git`.

## Start the stack

1. Clone the repositories next to each other:
   ```
   empiriq/
     database/
     binance-backfiller/
     binance-feeder/
     board/
     empiriq/
   ```
2. In the `empiriq` repository, build the images and start the stack:
   ```sh
   make build && make up
   make open        # the board: http://localhost:3000, no login
   ```

`make` creates `.env` from `.env.example` the first time. To build from
GitHub without the clones, run `make build IMAGE_SOURCE=git`.

## Follow the logs

```sh
make logs S=board    # one service
make logs            # all services
```

The board shows the logs of all services on the page **Logs**.

## Change a service

The stack mounts no code. Build the image again and restart the service:

```sh
make rebuild S=binance-feeder
```

This also works for the dashboards of the board (`S=board`). It takes
seconds: the image only copies the files.

## Change the migrations

The migrations run only on an empty database. Build the image of the
database and start from a new database:

```sh
make build S=database && make reset
```

`make reset` deletes all data: the database, the settings and users of
Grafana.

## Change a setting

- **A setting of the stack:** change it in `.env`, then run `make restart`.
- **A setting of one service only on your computer:** put it into
  `env/<service>.local.env`. Git ignores these files. For example, a setting
  of Grafana as `GF_<SECTION>_<KEY>` in `env/board.local.env`.

Change the passwords before you run the stack on a server.

## Add a service

1. Add the service to `compose.yaml` with `env_file`: its own
   `env/<service>.env`, `env/database-client.env` if it uses the database,
   and the optional `env/<service>.local.env`.
2. A feeder or a backfiller sets `EMPIRIQ_DATABASE_ROLE=empiriq_feeder` in
   its own file.
3. Add the image to `docker-bake.hcl` and to `IMAGES` in the `Makefile`.

## Use a login for each service

On a real installation, create a login for each service with its role
([ADR 0001](../adr/0001-database-is-the-api.md)):

```sql
create role binance_feeder login password '...' in role empiriq_feeder;
create role board login password '...' in role empiriq_operator;
```

Then set `EMPIRIQ_DATABASE_USER` and `EMPIRIQ_DATABASE_PASSWORD` of each
service in `env/<service>.local.env`, and leave `EMPIRIQ_DATABASE_ROLE` empty.

## Run the tests of a service

The tests of the services do not use the stack. `make smoke` in `database`
builds and runs its own test image. `make test-integration` in
`binance-backfiller` and `binance-feeder` starts the image
`empiriq/database:dev` with the data in memory.
