# empiriq stack: builds the images of all repositories and runs them together
#   make build   build the images (docker buildx bake, docker-bake.hcl)
#   make up      start the database and all services
#   make rebuild build the images again and restart what changed
#   make help    list all targets
#
# Settings come from .env (see .env.example).

-include .env

# compose.yaml takes all settings from .env. Without it, make creates it
# from .env.example.
.env:
	cp .env.example $@
	@echo "created .env from .env.example"

# Git revisions for the image labels, from the repositories next to this one.
rev = $(shell git -C $(1) rev-parse --short HEAD 2>/dev/null)
ifeq ($(IMAGE_SOURCE),local)
DATABASE_REVISION           ?= $(call rev,$(DATABASE_DIR))
BINANCE_BACKFILLER_REVISION ?= $(call rev,$(BINANCE_BACKFILLER_DIR))
BINANCE_FEEDER_REVISION     ?= $(call rev,$(BINANCE_FEEDER_DIR))
BOARD_REVISION              ?= $(call rev,$(BOARD_DIR))
# Bake asks before it reads a directory outside this repository: allow
# exactly the repositories it builds.
BAKE_ALLOW = $(foreach d,$(DATABASE_DIR) $(BINANCE_BACKFILLER_DIR) $(BINANCE_FEEDER_DIR) $(BOARD_DIR),--allow=fs.read=$(d))
endif

# docker buildx bake and docker compose read these from the environment.
export IMAGE_SOURCE IMAGE_GITHUB_ORG IMAGE_REGISTRY IMAGE_PULL_POLICY \
       DATABASE_DIR BINANCE_BACKFILLER_DIR BINANCE_FEEDER_DIR BOARD_DIR \
       DATABASE_REVISION BINANCE_BACKFILLER_REVISION BINANCE_FEEDER_REVISION BOARD_REVISION

DB_IMAGE = $(IMAGE_REGISTRY)empiriq/database:dev
IMAGES = $(DB_IMAGE) $(foreach i,binance-backfiller binance-feeder board,$(IMAGE_REGISTRY)empiriq/$(i):dev)

URL          = http://localhost:$(BOARD_PORT)
COMPOSE      = docker compose
PSQL         = $(COMPOSE) exec -T database sh -c 'psql -U "$$POSTGRES_USER" -d "$$POSTGRES_DB" -v ON_ERROR_STOP=1 -X'

# History requests: make request SYMBOL=BTCUSDT FROM=2026-06-01 TO=2026-06-02
VENUE  ?= binance-futures-um
SYMBOL ?= BTCUSDT
# Streams of a request, comma separated: STREAMS=trade,depth
STREAMS ?= trade
FROM   ?=
TO     ?=

# Instruments: make instrument VENUE=binance-spot SYMBOL=BTCUSDT KIND=spot
KIND  ?= perpetual
BASE  ?= BTC
QUOTE ?= USDT

# make logs S=board, make restart S=binance-backfiller, make build S=board
S ?=

##@ General

.PHONY: help
help: ## Display this help.
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z_0-9-]+:.*?##/ { printf "  \033[36m%-12s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

##@ Images

.PHONY: build
build: ## Build all images, or one with S=<image>. IMAGE_SOURCE=git builds from GitHub.
	docker buildx bake -f docker-bake.hcl $(BAKE_ALLOW) --load $(S)

.PHONY: images
images: ## Show the built images of empiriq.
	@docker image ls --filter 'reference=$(IMAGE_REGISTRY)empiriq/*'

##@ Stack

.PHONY: up
up: check-images ## Start the database and all services from the built images.
	@# --remove-orphans: containers of services that compose.yaml no longer has,
	@# for example after a rename, would keep their ports.
	$(COMPOSE) up -d --remove-orphans
	@echo "board: $(URL)"

.PHONY: check-images
check-images:
	@# Without a registry (IMAGE_PULL_POLICY never), every image must be built.
	@if [ "$(IMAGE_PULL_POLICY)" = never ]; then for i in $(IMAGES); do \
	  docker image inspect $$i >/dev/null 2>&1 || { echo "no image $$i: run make build"; exit 1; }; \
	done; fi

.PHONY: rebuild
rebuild: ## After a change of the repositories: build all images, or one with S=<image>, and restart the services whose image changed.
	@# The migrations run only on a new volume. If the image of the database
	@# changed, the running database keeps its old schema: say so.
	@old=$$(docker image inspect -f '{{.Id}}' $(DB_IMAGE) 2>/dev/null); \
	$(MAKE) --no-print-directory build && \
	$(COMPOSE) up -d --remove-orphans $(S) && \
	new=$$(docker image inspect -f '{{.Id}}' $(DB_IMAGE) 2>/dev/null) && \
	if [ -n "$$old" ] && [ "$$old" != "$$new" ]; then \
	  echo "The image of the database changed. Its migrations run only on a new database:"; \
	  echo "run make reset to apply them (it deletes all data)."; \
	fi
	@echo "board: $(URL)"

.PHONY: down
down: ## Stop all services (data stays).
	$(COMPOSE) down

.PHONY: restart
restart: check-images ## Restart all services, or one with S=<service>, with the current images.
	$(COMPOSE) up -d --force-recreate $(S)

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
open: ## Open the board in the browser.
	@open $(URL) 2>/dev/null || xdg-open $(URL) 2>/dev/null || echo $(URL)

.PHONY: lint
lint: ## Check compose.yaml and docker-bake.hcl.
	@$(COMPOSE) config -q && docker buildx bake -f docker-bake.hcl --print >/dev/null && echo ok

##@ Database

.PHONY: psql
psql: ## Open psql in the database.
	$(COMPOSE) exec database sh -c 'psql -U "$$POSTGRES_USER" -d "$$POSTGRES_DB"'

.PHONY: sql
sql: ## Run a SQL file in the database, for example make sql F=examples/01-live-trades.sql.
	@test -n "$(F)" || { echo "set F, for example F=examples/01-live-trades.sql"; exit 1; }
	@$(PSQL) < $(F)

##@ Inspect

.PHONY: strategies
strategies: ## Show strategies: desired status and last strategy run.
	@echo "select * from api.strategy_status order by strategy;" | $(PSQL)

.PHONY: worlds
worlds: ## Show worlds: clock, accounts and the open frame.
	@echo "select id, name, clock, step, replay_to, accounts, open_frame_id, open_as_of, pending, last_as_of from api.world order by id;" | $(PSQL)

.PHONY: frames
frames: ## Show the last 20 frames of all worlds.
	@echo "select id, world, as_of, pending, opened_at, closed_at, duration from api.frame order by id desc limit 20;" | $(PSQL)

.PHONY: strategy-runs
strategy-runs: ## Show the last 20 strategy runs.
	@echo "select id, frame_id, strategy, as_of, status, intents, duration, left(error, 60) as error from api.strategy_run order by id desc limit 20;" | $(PSQL)

.PHONY: writers
writers: ## Show writers of market data.
	@echo "select id, name, kind, alive, heartbeat_age, registered_at from api.writer order by id;" | $(PSQL)

.PHONY: streams
streams: ## Show the coverage of all streams: live and history.
	@echo "select venue_id, symbol, stream, covered_from, covered_to, uncovered, requested_uncovered, lag, heartbeat_age, running from api.stream_summary order by 1, 2, 3;" | $(PSQL)

.PHONY: coverage
coverage: ## Show requested, covered and missing history.
	@echo "select venue_id, symbol, stream, covered, missing, running, failed from api.history_coverage order by 1, 2, 3;" | $(PSQL)

.PHONY: delays
delays: ## Show the publish delays measured from live trades.
	@echo "select venue_id, stream, delay, samples, measured_from, measured_to, updated_at from api.publish_delay order by 1, 2;" | $(PSQL)

.PHONY: runs
runs: ## Show the last 20 backfill runs (internal, ADR 0011).
	@echo "select b.id, w.name as writer, i.venue_id, i.symbol, b.stream, b.period, b.status, b.rows, b.error from market.backfill_run b join core.instrument i on i.id = b.instrument_id join market.writer w on w.id = b.writer_id order by b.id desc limit 20;" | $(PSQL)

.PHONY: backfillers
backfillers: ## Show registered backfillers: capabilities and heartbeat.
	@echo "select name, capabilities, alive, heartbeat_age from api.writer where kind = 'backfiller' order by name;" | $(PSQL)

##@ Live

.PHONY: feeders
feeders: ## Show registered feeders: capabilities and heartbeat.
	@echo "select name, capabilities, alive, heartbeat_age from api.writer where kind = 'feeder' order by name;" | $(PSQL)

.PHONY: live
live: ## Show live requests: status, feeders and lag.
	@echo "select id, venue_id, symbol, stream, desired_status, feeders, complete_until, lag, covered_since from api.live_request order by id;" | $(PSQL)

.PHONY: live-request
live-request: ## Request live data of SYMBOL on VENUE (STREAMS), for example make live-request VENUE=binance-spot SYMBOL=BTCUSDT STREAMS=trade,depth.
	@echo "select api.add_live_request(api.instrument_id('$(VENUE)', '$(SYMBOL)'), '{$(STREAMS)}');" | $(PSQL)

##@ History

.PHONY: instrument
instrument: ## Register an instrument (VENUE, SYMBOL, KIND, BASE, QUOTE).
	@echo "select api.register_instrument('$(VENUE)', '$(SYMBOL)', '$(KIND)', '$(BASE)', '$(QUOTE)');" | $(PSQL)

.PHONY: request
request: ## Request history (VENUE, SYMBOL, STREAMS, FROM, TO), for example FROM=2026-06-01 TO=2026-06-02.
	@test -n "$(FROM)" -a -n "$(TO)" || { echo "set FROM and TO, for example FROM=2026-06-01 TO=2026-06-02"; exit 1; }
	@echo "select api.add_history_request(api.instrument_id('$(VENUE)', '$(SYMBOL)'), '{$(STREAMS)}', '$(FROM)', '$(TO)');" | $(PSQL)

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
