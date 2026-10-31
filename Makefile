# empiriq stack: the database and all services
#   make help    list all targets
#
# The services are built from the repositories next to this one. Settings
# come from .env (see .env.example).

-include .env

CONSOLE_PORT ?= 3000
URL          = http://localhost:$(CONSOLE_PORT)
COMPOSE      = docker compose
PSQL         = $(COMPOSE) exec -T empiriq sh -c 'psql -U "$$POSTGRES_USER" -d "$$POSTGRES_DB" -v ON_ERROR_STOP=1 -X'

# History requests: make request SYMBOL=BTCUSDT FROM=2026-06-01 TO=2026-06-02
VENUE  ?= binance-futures-um
SYMBOL ?= BTCUSDT
STREAM ?= trade
FROM   ?=
TO     ?=
DELAY  ?= 0

# Instruments: make instrument VENUE=binance-spot SYMBOL=BTCUSDT KIND=spot
KIND  ?= perpetual
BASE  ?= BTC
QUOTE ?= USDT

# make logs S=console, make restart S=binance-backfiller
S ?=

##@ General

.PHONY: help
help: ## Display this help.
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z_0-9-]+:.*?##/ { printf "  \033[36m%-12s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

##@ Stack

.PHONY: up
up: ## Build and start the database, the logins and all services.
	$(COMPOSE) up -d --build
	@echo "console: $(URL)"

.PHONY: down
down: ## Stop all services (data stays).
	$(COMPOSE) down

.PHONY: restart
restart: ## Rebuild and restart all services, or one with S=<service>.
	$(COMPOSE) up -d --build --force-recreate $(S)

.PHONY: reset
reset: ## Start from a clean slate: delete the database and Grafana data, create the database again from the migrations.
	$(COMPOSE) down -v --remove-orphans
	$(MAKE) up

.PHONY: status
status: ## Show the containers.
	$(COMPOSE) ps -a

.PHONY: logs
logs: ## Follow the logs of all services, or of one with S=<service>.
	$(COMPOSE) logs -f $(S)

.PHONY: open
open: ## Open the console in the browser.
	@open $(URL) 2>/dev/null || xdg-open $(URL) 2>/dev/null || echo $(URL)

.PHONY: lint
lint: ## Check compose.yaml.
	@$(COMPOSE) config -q && echo ok

##@ Database

.PHONY: psql
psql: ## Open psql in the database.
	$(COMPOSE) exec empiriq sh -c 'psql -U "$$POSTGRES_USER" -d "$$POSTGRES_DB"'

.PHONY: logins
logins: ## Create the database logins of the services again, for example after a manual restore.
	$(COMPOSE) run --rm logins

##@ Inspect

.PHONY: strategies
strategies: ## Show strategies: desired status and last frame.
	@echo "select * from api.strategy_status order by strategy;" | $(PSQL)

.PHONY: frames
frames: ## Show the last 20 frames.
	@echo "select id, strategy, as_of, status, intents, duration, left(error, 60) as error from api.frame order by id desc limit 20;" | $(PSQL)

.PHONY: writers
writers: ## Show writers of market data.
	@echo "select id, name, kind, open, created_at, closed_at from api.writer order by id;" | $(PSQL)

.PHONY: streams
streams: ## Show the coverage of all streams: live and history.
	@echo "select venue_id, symbol, stream, covered_from, covered_to, uncovered, missing, lag, heartbeat_age, running from api.stream_summary order by 1, 2, 3;" | $(PSQL)

.PHONY: coverage
coverage: ## Show requested, covered and missing history.
	@echo "select venue_id, symbol, stream, covered, missing, running, failed from api.history_coverage order by 1, 2, 3;" | $(PSQL)

.PHONY: runs
runs: ## Show the last 20 backfill runs.
	@echo "select writer_id, venue_id, symbol, period, status, rows, rows_per_second, error from api.backfill_run order by writer_id desc limit 20;" | $(PSQL)

.PHONY: backfillers
backfillers: ## Show registered backfillers: capabilities, heartbeat and results of 24 hours.
	@echo "select id, name, capabilities, alive, heartbeat_age, running, done_24h, failed_24h, rows_24h from api.backfiller order by id;" | $(PSQL)

##@ Live

.PHONY: feeders
feeders: ## Show registered feeders: capabilities, heartbeat and streams.
	@echo "select id, name, capabilities, alive, heartbeat_age, streams from api.feeder order by id;" | $(PSQL)

.PHONY: live
live: ## Show live requests: status, feeders and lag.
	@echo "select id, venue_id, symbol, stream, desired_status, feeders, complete_until, lag, covered_since from api.live_request order by id;" | $(PSQL)

.PHONY: live-request
live-request: ## Request live data of SYMBOL on VENUE, for example make live-request VENUE=binance-spot SYMBOL=BTCUSDT.
	@echo "select api.add_live_request(api.instrument_id('$(VENUE)', '$(SYMBOL)'), '$(STREAM)');" | $(PSQL)

##@ History

.PHONY: instrument
instrument: ## Register an instrument (VENUE, SYMBOL, KIND, BASE, QUOTE).
	@echo "select api.register_instrument('$(VENUE)', '$(SYMBOL)', '$(KIND)', '$(BASE)', '$(QUOTE)');" | $(PSQL)

.PHONY: request
request: ## Request history (VENUE, SYMBOL, STREAM, FROM, TO, DELAY), for example FROM=2026-06-01 TO=2026-06-02.
	@test -n "$(FROM)" -a -n "$(TO)" || { echo "set FROM and TO, for example FROM=2026-06-01 TO=2026-06-02"; exit 1; }
	@echo "select api.add_history_request(api.instrument_id('$(VENUE)', '$(SYMBOL)'), '$(STREAM)', '$(FROM)', '$(TO)', interval '$(DELAY)');" | $(PSQL)

.PHONY: check
check: ## Check loaded trades of SYMBOL in [FROM, TO): count, id range, gaps, aggressor, delay.
	@test -n "$(FROM)" -a -n "$(TO)" || { echo "set FROM and TO, for example FROM=2026-06-01 TO=2026-06-02"; exit 1; }
	@printf '%s\n' \
	  "select count(*) as trades, min(first_trade_id) as first_id, max(last_trade_id) as last_id," \
	  "       max(last_trade_id) - min(first_trade_id) + 1 - count(*) as missing_ids," \
	  "       round(avg((aggressor = 'buy')::int), 3) as buy_share," \
	  "       min(trade_time) as first_time, max(trade_time) as last_time," \
	  "       min(event_time - trade_time) as min_delay, max(event_time - trade_time) as max_delay" \
	  "  from market.trade" \
	  " where instrument_id = api.instrument_id('$(VENUE)', '$(SYMBOL)')" \
	  "   and trade_time >= '$(FROM)' and trade_time < '$(TO)';" \
	  "select * from api.trade_gaps(api.instrument_id('$(VENUE)', '$(SYMBOL)'), '$(FROM)', '$(TO)') limit 10;" | $(PSQL)
