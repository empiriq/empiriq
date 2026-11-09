# empiriq

**Algorithmic trading where the database is the platform.**

empiriq keeps the current state of your trading accounts in PostgreSQL:
balances, positions and orders. It also keeps the market data for these
accounts. Strategies are SQL functions. They declare what the account
_should_ look like. Small external services load market data and reconcile the
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

- **Declarative.** You state _what_ you want: a balance, a position or a set
  of orders. Planners and reconcilers decide _how_.
- **Desired state and observed state.** Strategies never call the venue.
  Reconcilers make the account match the desired state, like Kubernetes
  controllers.
- **One code path for backtests and live trading.** Strategies use the logical
  time `as_of`, never `now()`.
- **The database is the API.** You can write feeders and reconcilers in any
  language that has a PostgreSQL driver.

## Quickstart

You need Docker with Compose, `make` and `git`. Clone the repositories of
empiriq next to each other: `database`, `binance-backfiller`,
`binance-feeder`, `board` and this one, `empiriq`. Then, in this repository:

```sh
make build && make up     # build the images and start everything
make open                 # the board: http://localhost:3000

make sql F=examples/01-live-trades.sql   # live trades of BTCUSDT
make sql F=examples/02-history.sql       # the trades of yesterday
make sql F=examples/03-strategy.sql      # a strategy on the history of yesterday
```

The tutorial [Your first strategy](docs/tutorials/first-strategy.md) explains
each step.

## Documentation

- [Your first strategy](docs/tutorials/first-strategy.md): a tutorial of 15
  minutes. Start here.
- [Run the stack](docs/how-to/run-the-stack.md): start, change and reset
  empiriq on your computer.
- [Architecture](docs/explanation/architecture.md): how and why empiriq works.
- [Glossary](docs/reference/glossary.md): the terms of the project.
- [Market data](docs/reference/market-data.md): the contract for feeders and
  backfillers.
- [Stack](docs/reference/stack.md): services, images, settings, logs and
  make targets.

[docs/README.md](docs/README.md) lists all documents and the rules for
writing them.

## Repositories

empiriq has one repository for each service. This repository has the
documentation of the project and runs all services together for trying and
testing.

| Repository           | What it is                                            |
| -------------------- | ----------------------------------------------------- |
| `database`           | PostgreSQL with the schema, the engine and `api`      |
| `binance-backfiller` | Loads history from the Binance archive                |
| `binance-feeder`     | Writes live trades from the Binance WebSocket streams |
| `board`              | Grafana with the board                                |
| `empiriq`            | The documentation, and the stack of all services      |
