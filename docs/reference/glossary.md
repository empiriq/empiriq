# Glossary

> Status: **draft**. The ubiquitous language of empiriq.

This page lists the words for the domain of empiriq. Use them in code and in
text. One concept has one name. If a term here and a name in code disagree,
one of the two has a bug.

- **Use** each term exactly as it is written here: in docs, SQL, Go, issues and
  commit messages.
- **Do not use** the listed synonyms, also when they seem clearer in one place.
- **New concept?** Add it here first, in the same pull request.
- **Never "run" or "request" alone.** Say *backfill run* or *strategy run*,
  *live request* or *history request*. A call to a venue is not a request.

Each entry has the term, its meaning, its name in code and the words to
avoid. A word in *italics* has its own entry on this page.

## Venues and accounts

| Term | Meaning | In code | Do not use |
|------|---------|---------|------------|
| **venue** | A place where orders are executed: an exchange, a broker or a simulator. | `core.venue`, `venue_id` | exchange (except as an example of a venue), market, broker |
| **account** | A trading account on one *venue*. All *observed state* and *desired state* belong to an account. | `core.account`, `account_id` | wallet, portfolio, sub-account |
| **asset** | Something that can be held in a *balance*: a coin, a currency, a token. | `core.asset`, `asset_id` | coin, currency, token |
| **instrument** | Something that you can trade on a venue. Its price is in the *quote asset* for one unit of the *base asset*. Kinds: `spot`, `perpetual`, `future`, `option`. | `core.instrument`, `instrument_id`, `api.report_instruments()` | market, pair, ticker, contract |
| **symbol** | The venue's own name for an *instrument* (`BTCUSDT`). Only used at the boundary; inside the database we use `instrument_id`. | `core.instrument.symbol`, Go `Symbol` | ticker |
| **base asset** / **quote asset** | The asset being bought or sold / the asset prices are expressed in. In `BTCUSDT`, BTC is base, USDT is quote. | `base_asset`, `quote_asset` | |
| **trading rules** | Limits of the venue for orders: the price step, the quantity step, the minimum notional. empiriq does not store them; the venue checks them, and the reconciler reports a rejection in `status_reason` ([ADR 0017](../adr/0017-venue-trading-rules.md)). | | filters, tick size and lot size (as stored values) |

## Market data

| Term | Meaning | In code | Do not use |
|------|---------|---------|------------|
| **market data** | Events about the market published by a venue: *trades* and *depth snapshots*. | schema `market` | ticks, feed data |
| **trade** | One row of `market.trade`. With raw trades, one *source trade*; the columns allow a range of source trades at one price. | `market.trade`, Go `Trade` | tick, print, aggTrade |
| **source trade** | A single match on the venue, identified by the venue's trade id. A *trade* represents one or more. | `first_trade_id`, `last_trade_id` | |
| **raw trade** | A source that delivers each source trade separately. empiriq uses raw trades for live and historical data. | `first_trade_id = last_trade_id` | |
| **aggressor** | The order side of the taker in a trade: `buy` if the buyer took liquidity. | `market.trade.aggressor` | taker side, direction |
| **depth snapshot** | The top N *price levels* of both *book sides* at one moment. A snapshot replaces the previous one. | `market.depth`, Go `Depth` | order book update, depth update, L2 |
| **price level** | One price in a depth snapshot and the *quantity* resting at that price only. Level 1 is the best. | `bid_price[i]`, `bid_qty[i]`, Go `DepthLevel` | level (alone), rung |
| **book side** | `bid` (buy orders resting) or `ask` (sell orders resting). Not the same as *order side*. | `bid_*`, `ask_*`, `api.depth_levels.side` | buy side, sell side |
| **best bid** / **best ask** | Level 1 of the bid / ask book side. | `api.last_quote().bid` / `.ask` | top of book, BBO, quote (see open decisions) |
| **notional** | Price × quantity, in the quote asset. Always derived, never stored. | `notional`, Go `Notional` | value, volume (volume is base asset) |
| **volume** | Sum of trade quantities in a period, in the base asset. | `api.trade_buckets().volume` | |
| **period** | A time bucket `[ts, ts + interval)`, aligned to UTC. | `date_bin(...)`, `api.trade_buckets().ts` | candle time, window |
| **bar** | Values of the trades in one *closed period*, such as open, high, low, close and volume. A strategy builds its bars from `api.trades()`; `api` does not build bars. | | candle, kline, OHLC (as a noun) |
| **trade bucket** | Open, high, low, close, volume and source of the trades in one period, for charts on the board. Ignores frames, and can be partial. Not a bar. | `api.trade_buckets()` | |
| **closed period** | A period that has ended by `as_of`: `ts + interval <= as_of`. A strategy builds bars only from closed periods. | | |
| **event time** | When the venue published an event. Market data is *visible* from this moment. | `event_time`, Go `EventTime` | timestamp, ts |
| **trade time** | When a trade happened on the venue. Used to put trades into periods. | `trade_time`, Go `TradeTime` | |
| **received time** | When the row reached the database. Used for latency monitoring only. | `received_at` | ingest time |
| **stream** | One instrument and one event type: `trade` or `depth`. Several writers can write it. | `market.stream`, `(instrument_id, stream)` | feed, channel, topic |
| **writer** | A process that writes market data: a feeder, or one backfill run. Every row has its writer. | `market.writer`, `writer_id`, `api.register_feeder()` | source, producer, client |
| **close** (a writer) | Mark a writer as finished: all its rows are committed, and it writes no more. Frames that start later see all its rows. | `api.close_writer()`, `close_order` | seal, finish |
| **backfill run** | Loading of one stream for one period. It is its own writer. Done runs form the coverage. Internal: the operator sees history requests, not runs ([ADR 0011](../adr/0011-board.md)). | `market.backfill_run`, `api.finish_backfill()` | job, import |
| **live request** | Desired live data: one stream of one instrument that feeders write all the time. An operator can pause, resume or withdraw it. | `market.live_request`, `api.add_live_request()`, `api.live_streams()` | subscription (that is for strategies), feed |
| **history request** | Desired history: one stream for one period. An operator can pause, resume or withdraw it. | `market.history_request`, `api.add_history_request()`, `api.pause_history_request()`, `api.withdraw_history_request()` | order, job |
| **capability** | One venue and one stream that a feeder can write or a backfiller can load. A feeder gets only the live requests of its capabilities, and a backfiller leases only periods of its capabilities. | `market.feeder_stream`, `market.backfiller_stream` | skill, feature |
| **heartbeat** (of a service) | A sign that a service is alive, sent every `heartbeat_interval`. A stream has its own heartbeat: the *watermark*. | `api.backfiller_heartbeat()`, `api.feeder_heartbeat()`, `heartbeat_at` | ping, keepalive |
| **retry** (a history request) | Let backfillers lease the failed periods of a request now, without waiting for `retry_after`. | `api.retry_history_request()`, `retry_requested_at` | rerun, restart |
| **coverage** | The periods of a stream for which every event is stored. *History coverage*: the union of the periods of all done backfill runs. With *live coverage*: the coverage of the stream. | `api.history_coverage`, `api.stream_summary.covered` | availability |
| **live coverage** | A period in which one feeder stored every event of a stream, by `event_time`. `api.report_watermark()` extends it, or starts a new one after a reconnect or a long jump of `complete_until`. | `market.live_coverage`, `api.live_coverage` | uptime |
| **missing period** | A requested period without history coverage: history requests − history coverage. Compares desired state with observed state. Backfillers lease missing periods. | `api.history_coverage.missing` | gap, hole |
| **uncovered period** | A period between the first and the last covered time of a stream that has no coverage. Compares with what is stored, not with requests: lost live data, or history that was not loaded. Not a *gap*: a gap is about trade ids. | `api.stream_summary.uncovered` | hole, outage |
| **lease** (verb) | What a backfiller does with `api.lease_history()`: it takes a *missing period* that it can load, as a new backfill run. The run is abandoned when the backfiller sends no heartbeat for `stale_after`. | `api.lease_history()` | claim (claim is for strategies), lock, task; *lease* as a noun: say *backfill run* |
| **publish delay** | Added to `trade_time` to get `event_time` for historical trades, because the source has no publish time. Measured from live trades of the venue: the 99th percentile of `event_time - trade_time`. | `market.publish_delay`, `api.publish_delay`, `market.backfill_run.publish_delay` | latency, lag |
| **gap** | A range of source trade ids with no row. Not always lost data: some sources skip ids. | `api.trade_gaps()` | hole, missing data |
| **seq** | Number that a market row gets at insert. Grows in commit order for each writer and instrument. | `market.trade.seq`, `market.depth.seq` | id, offset |
| **visible** | A market row is visible in a *frame* when `event_time <= as_of`, and its writer was either open with `seq <=` its *cutoff*, or closed before the frame. Outside a frame, only the first condition applies. | | known, available |
| **watermark** | Heartbeat of a stream: the time up to which every event is stored. Feeders update it also in a quiet market. Each report also records *live coverage*. | `market.watermark`, `api.report_watermark()` | checkpoint, offset |

## Orders and executions

| Term | Meaning | In code | Do not use |
|------|---------|---------|------------|
| **order** | An instruction to buy or sell on a venue. Without a qualifier, "order" means an *observed order*. | `core."order"` | |
| **order side** | `buy` or `sell`. Not the same as *book side*. | `core.order_side` | direction |
| **quantity** | An amount of the base asset. | `qty` in SQL, `Quantity` in Go | size, amount, volume (see open decisions) |
| **fill** | One execution of (part of) an order, as reported by the venue. | `core.fill`, `api.report_fill()` | execution, trade (a fill is ours, a trade is the market's) |
| **order status** | Where an observed order is in its lifecycle. *Terminal*: `filled`, `canceled`, `rejected`, `expired`. | `core.order_status` | state |
| **unknown order** | An order whose venue status is lost (for example after a timeout). It must be *resolved*. | status `unknown` | lost, orphan |
| **client order id** | Our deterministic id for an order, derived from strategy, *order key* and *revision*. Makes retries idempotent. | `client_order_id` | cid, clOrdId |
| **managed order** | An order created by empiriq (client order id starts with `eq`). | `origin = 'managed'` in `api.order_action` | own order |
| **foreign order** | An order on a managed account that empiriq did not create. Cancelled only if the account allows it. | `cancel_foreign_orders` | external, manual |

## Intent and state

| Term | Meaning | In code | Do not use |
|------|---------|---------|------------|
| **observed state** | What the venue says is true: balances, positions, orders, fills. Written only by reconcilers. | `core.balance`, `core.position`, `core."order"`, `core.fill` | actual state, current state, real state |
| **desired state** | What strategies want to be true. Written only by the engine. | `core.desired_*` | target state, wanted state, plan |
| **balance** | How much of an asset an account holds (`total`, `available`). | `core.balance` | holdings, funds |
| **position** | Signed net exposure on a derivative instrument. For spot, exposure is the base asset balance. | `core.position.qty` | exposure (as a table name), holding |
| **intent** | One row of strategy output. Kinds: `balance`, `position`, `order`, `state`. | `api.intent`, `api.target_*`, `api.*_order`, `api.set_state` | signal, command, action |
| **full replace** | The output of a strategy run replaces the complete desired state of that strategy. A missing asset or instrument is no longer managed. | `core.apply_intents()` | snapshot semantics |
| **intent kind** | `balance`, `position`, `order` or `state`. The first three go from the most declarative to the least: `balance` → `position` → `order`. A claim has one of these three. | `api.intent.kind`; `core.claim.level` (to be renamed `kind`) | intent level, layer |
| **target** | The desired amount in a balance or position intent. | `target` | goal, desired quantity |
| **tolerance** | A delta at or below this does not cause an order. Prevents churn. Set it to at least one quantity step of the venue. | `tolerance` | threshold, epsilon, deadband |
| **hints** | Free-form execution preferences passed through to planners and reconcilers. | `hints jsonb` | options, params |
| **order key** | A strategy's own stable name for an order intent. Same key + same fields = no action. | `api.intent.key`, `core.desired_order.key` | order name, tag |
| **revision** | Increments when an order intent with the same key changes. A new revision means a new client order id. | `core.desired_order.revision` | version, generation |
| **satisfied** | A desired order whose observed order is terminal. It is not placed again. | | done, completed |
| **claim** | Ownership of an asset or instrument in an account by one strategy with one *intent kind*. | `core.claim` | lock, reservation |
| **planner** | Engine code that turns balance and position targets into desired orders. | `core.plan()`, `origin = 'planner'` | optimizer, allocator |
| **delta** | `target − observed − in-flight`. What a planner still has to trade. | | diff, gap |
| **in-flight** | The unfilled quantity of planner orders that are desired or live. | | pending, open |
| **strategy** | A registered SQL function with params, bound to one account. It maps a strategy context to intents. Works like a Kubernetes Deployment. | `core.strategy`, schema `strategies` | algo, bot, model, deployment |
| **desired status** | `running` or `stopped`. Only running strategies get frames. | `core.strategy.desired_status`, `api.set_strategy_status()` | enabled, active |
| **on stop** | What happens to the desired state of a stopped strategy: `keep` or `cancel`. | `core.strategy.on_stop` | |
| **subscription** | A stream that a strategy reads. The engine records cutoffs only for subscribed streams. | `core.strategy_stream`, `api.subscribe()` | dependency, input |
| **strategy run** | One run of one strategy in a frame. Reserved: its details come with the decision about the frame handler. | | run (alone) |
| **strategy context** | The input of a strategy: ids, `as_of`, clock, params, strategy state. | `api.strategy_context`, `ctx` | |
| **strategy state** | Data a strategy carries between frames. Always say "strategy state", never just "state". | `core.strategy_state`, `api.set_state()` | memory, storage |
| **params** | Static configuration of a registered strategy. | `core.strategy.params` | config, settings, arguments |

## Frames and services

| Term | Meaning | In code | Do not use |
|------|---------|---------|------------|
| **world** | A set of *accounts* with one *clock*. Frames belong to a world. Several worlds can run in one database and share the market data. | `core.world`, `world_id`, `api.start_world()` | environment, universe, sandbox |
| **reconciliation** | One completed pass of a reconciler over an account in a *frame*. It confirms the account for that frame ([ADR 0022](../adr/0022-frames-belong-to-a-world.md)). | `core.reconciliation`, `api.reconciled()` | sync, cycle |
| **frame** | One step of a *world* at one `as_of`. It ends when the last account of the world is reconciled: then the *frame handler* runs and the next frame opens ([ADR 0022](../adr/0022-frames-belong-to-a-world.md)). Today `core.frame` still stores one run of one strategy. | `core.frame`, `api.frame` | tick, cycle, round, iteration, step |
| **frame handler** | Engine code that runs at the end of a frame, in the transaction of the last reconciliation. Its first version runs the strategies and the planners of the world. | `core.on_frame()` | callback, hook |
| **cutoff** | For a subscribed stream and a writer that is open at frame start: its highest `seq` (0 if none). A frame sees no row of that writer above it. A replay with the same cutoffs sees the same rows. | `core.frame_cutoff.seq_cutoff` | snapshot, watermark |
| **as_of** | The logical time of a frame. Strategies use it instead of `now()`. Increases for each world. | `as_of`, `ctx.as_of`, `api.now()` | now, current time, timestamp |
| **clock** | How `as_of` of a *world* moves: `live` follows the wall clock, `replay` moves through history step by step ([ADR 0022](../adr/0022-frames-belong-to-a-world.md)). A *live world* or a *replay world* is a world with that clock. Today the code has a mode on the account instead (`live`, `paper`, `backtest`). | `core.world.clock` (planned); today `api.mode`, `core.account.mode` | mode, environment, profile |
| **backtest** | A run of strategies over history, in a replay world. A use of a replay world, not a clock. | | simulation |
| **paper trading** | Trading in a live world with simulated accounts. | | paper mode |
| **account event** | One report of a reconciler: order, fill, balance or position. Stored in a journal. | `core.account_event`, `api.account_event` | update, message |
| **feeder** | A long-running service that writes live market data from one venue or data source. Identified by its name. Registers with its capabilities, sends heartbeats and writes the active live requests. Has no desired state. | service `feeder`, `api.register_feeder()`, `api.feeder` | collector, ingester, connector, livefiller |
| **backfiller** | A long-running service that loads requested history in backfill runs. Identified by its name. Registers with its capabilities and sends heartbeats. Has no desired state: empiriq does not start or stop it. | service `backfiller`, `api.register_backfiller()`, `api.backfiller` | importer, loader, replay feeder |
| **reconciler** | A long-running service that reconciles accounts with a venue, reports observed state and starts frames with `api.reconciled()`. | service `reconciler` | executor, trader, broker, connector, gateway |
| **engine** | The SQL code that runs frames and planners. | `core.run_frames()`, `core.plan()` | runtime, core (core is a schema) |

## Services

| Term | Meaning | In code | Do not use |
|------|---------|---------|------------|
| **service** | A part of empiriq that runs in its own container: the *database*, or a service of one kind: *feeder*, *backfiller*, *reconciler* or *auxiliary service*. Each service has its own repository. | service names in `compose.yaml` | component, app, microservice |
| **database** | The service with PostgreSQL: schema, engine and `api`. The contract for all other services. | service and repository `database` | db, backend |
| **auxiliary service** | A service that is not the database, a feeder, a backfiller or a reconciler, for example the *board*. It uses only `api` and has no contract of its own. | | helper, sidecar, tool |
| **empiriq** | The name of the project, of its GitHub organization and of the repository with the documentation. Not a service. | repository `empiriq` | |
| **board** | The user interface of empiriq: Grafana with the dashboards of empiriq. Shows the state and changes desired state, only through `api`. | service and repository `board` | board, dashboard (as a service name), UI, admin panel |
| **dashboard** | One page of the board, for example *Overview* or *History*. Never the board itself. | Grafana dashboard | screen, view (view is SQL), board (for one page) |
| **operator** | A person or the board that changes desired state through `api` functions. | role `empiriq_operator` | admin, user (as a role) |
| **log store** | The container of the stack that stores the logs of all containers: Loki. Not a *service*: it has no repository of its own and does not use `api`. | compose service `log-store` | |
| **log agent** | The container of the stack that reads the output of all containers and sends it to the log store: Alloy. Not a *service*. | compose service `log-agent` | log collector, log shipper |

## Reconciliation

| Term | Meaning | In code | Do not use |
|------|---------|---------|------------|
| **reconcile** | Make observed state match desired state on the venue. The reconciler's job. | | sync, execute |
| **order action** | One step of reconciliation: `place`, `cancel` or `resolve`. | `api.order_action` | command, diff row |
| **place** / **cancel** / **resolve** | Send a desired order / cancel a live order nobody wants / query the venue about an unknown order. | `action` | submit, create, delete, sync |
| **report** | A reconciler writing observed state into the database. | `api.report_*` | update, push |

## Open decisions

Some terms have two meanings today, or two names. Until we decide, use the
qualified form from the first column.

| Issue | Today | Proposal |
|-------|-------|----------|
| **"quote"** has two meanings: the quote asset of an instrument, and best bid/ask | `quote_asset` and `api.quote` / `api.last_quote()` | Keep "quote" only for the asset. Rename `api.quote` → `api.best_prices`, `api.last_quote()` → `api.best_prices()` |
| **"snapshot"** meant both a depth snapshot and the full-replace rule for strategy output | Docs now use **full replace**. Code has no name for the rule. | Keep "snapshot" only for depth snapshots |
| **`qty` and `Quantity`** | SQL `qty`, Go `Quantity` | Term is "quantity". Keep the `qty` abbreviation in SQL, or rename columns to `quantity` everywhere |
| **"side"** for order side and book side | `core.order_side`, `api.depth_levels.side` | Rename the depth column to `book_side` |
| **"state"** for strategy state, observed/desired state and order status | | Always qualify: strategy state, observed state, desired state; order *status* |
