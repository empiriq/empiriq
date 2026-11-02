# Your first strategy

In this tutorial you start empiriq on your computer, load live and historical
trades of Binance, and run a strategy on the history of yesterday. It takes
about 15 minutes. Most of it is waiting for the history to load.

You need Docker with Compose, `make` and `git`. Nothing is sent to Binance:
the services only read public market data, and the strategy runs on a paper
account.

## 1. Get the repositories

empiriq has one repository for each service. Clone them next to each other,
so that the `stack` repository finds them:

```
empiriq/
  empiriq/              the database: schema, engine and api
  binance-feeder/       live trades from Binance
  binance-backfiller/   history from the Binance archive
  console/              the console
  stack/                this repository
```

## 2. Start empiriq

In `stack`:

```sh
make build    # build the images of all repositories: a few minutes the first time
make up       # start the database and all services
make open     # the console: http://localhost:3000
```

`make up` starts the database first, then the services. At start, the feeder
and the backfiller report the instruments of their venues. After a few
seconds, the page **Instruments** of the console lists thousands of them.

## 3. Write live trades

Live data is declarative: you add a *live request*, and every feeder that can
write the stream writes it ([ADR 0016](../adr/0016-live-requests.md)).

```sh
make sql F=examples/01-live-trades.sql
```

The file adds a live request for the trades of `BTCUSDT` on Binance USDⓈ-M
futures. It shows the request and the newest trades. Run it again after a few
seconds: new trades appear.

In the console, open **Live requests** to see the request and its feeder.
Then open **Market data** and click `BTCUSDT` in the table **Streams**. The
page **Chart** shows the candles of the trades as they arrive.

## 4. Load the history of yesterday

History is declarative too. A *history request* says which period is needed.
The backfiller loads what is missing ([ADR 0010](../adr/0010-declarative-history.md)).

```sh
make sql F=examples/02-history.sql
```

The file adds a history request for yesterday, 00:00 to 24:00 UTC, and shows
the progress. Run it again until `missing` is empty: `{}`. A day of `BTCUSDT`
has more than a million trades, so this takes a few minutes.

In the console, **History requests** and **Market data** show the same
progress.

## 5. Run a strategy

A strategy is a SQL function. It reads market data and returns the state that
the account *should* have: here, a position. The example
`strategies.sma_cross` wants a position of 0.01 BTC while the short moving
average is above the long one, else no position.

```sh
make sql F=examples/03-strategy.sql
```

The file does four things:

1. It creates the account `demo` in the paper mode.
2. It registers the strategy `sma-demo` on this account, with its parameters,
   and subscribes it to the trades of `BTCUSDT`.
3. It runs a *frame* of the strategy for each hour of yesterday. A frame is
   one run of all strategies of an account at one time `as_of`. The strategy
   sees only the trades up to `as_of`, so the same frames give the same
   result tomorrow.
4. It shows the frames and the desired position after the last frame.

Usually a reconciler starts the frames: after each check of the account, it
calls `api.reconciled()`. empiriq has no reconciler yet, so the file calls
`api.reconciled()` itself, with times in the past.

## 6. Change the strategy

Open `examples/03-strategy.sql` and change the parameters, for example
`'fast', 10, 'slow', 60`. Run the file again: `api.register_strategy()` works
like "apply", so it updates the strategy. The frames of yesterday exist
already, so no new frames run. To run them again, start from a clean database:

```sh
make reset    # deletes all data: the database and the settings of Grafana
```

Then run the three files again. To write your own strategy, copy
`strategies.sma_cross` from the migrations of the `empiriq` repository
(`migrations/0008_example_strategies.sql`) and read
[Writing a strategy](../explanation/architecture.md#writing-a-strategy).

## What you learned

- empiriq keeps market data, desired state and observed state in PostgreSQL.
  Everything is controlled with SQL through the schema `api`.
- Live requests and history requests declare which data is needed. Feeders
  and backfillers do the work.
- A strategy is a function from a context to the desired state. Frames run
  it at a logical time `as_of`, so a run on history works like a run on live
  data.

Next: read the [architecture](../explanation/architecture.md), or the
[glossary](../reference/glossary.md) for the terms.
