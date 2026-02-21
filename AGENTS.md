# AGENTS.md

## Scope
Local instructions for automated code changes in this repository.

## Project Map
- Runtime entrypoint: `demo/bin/trade` (boots `App\Kernel`, resolves `Empiriq\Contracts\Runner`).
- Core runtime contracts: `src/Contracts`.
- Market integration and DI compiler passes: `src/BinanceTradeBundle`.
- Event and dependency discovery utilities: `src/SymfonyEventDiscovery`, `src/SymfonyDependencyDiscovery`.
- Tick generation bundle: `src/TickerBundle`.
- Demo app wiring and env defaults: `demo/config`.

## Core Engineering Rules
- Preserve backward compatibility for classes/methods marked as public API (`@api`) unless explicitly requested.
- Prefer compile-time DI assembly (compiler passes + definitions) over runtime reflection/config mutation.
- Keep event subscribers/listeners in application code intact; infrastructure should adapt around them.
- Keep event names stable and parseable via URL components (`path + query`).

## Event Subscription Contract
- Binance event format: `binance.<market>.<scope>.<type>?symbol[]=btcusdt&symbol[]=ethusdt`.
- Ticker event format: `ticker.interval?second=1.5`.
- `path` defines event kind; `query` defines generation/filter parameters.
- The same string is used in two roles:
- as Symfony subscription key (`getSubscribedEvents()` / listener event name)
- as container-build instruction for stream/clock registration

## Event Delivery Pipeline
- Producers (WS clients, `Runner`) publish through `Empiriq\Contracts\Messaging\EventPublisherInterface`.
- Publisher wraps payload into `Empiriq\Contracts\Messaging\DomainEventMessage`.
- Messenger bus handles the message (demo default transport: `sync://`).
- `DomainEventMessageHandler` forwards into Symfony `EventDispatcherInterface`.

## Quality Gates
- Style: `vendor/bin/phpcs --standard=PSR12 src`
- Static analysis: `vendor/bin/psalm --config=etc/psalm.xml --show-info=true --no-progress`
- Tests: `vendor/bin/phpunit --configuration=etc/phpunit.xml --cache-directory=var`
- CI reference: `.github/workflows/quality.yml`

## Change Checklist
- If event flow changes, update:
- `README.md`
- `src/BinanceTradeBundle/README.md`
- relevant files in `demo/config/packages/`
- If event naming grammar changes, update parser points in compiler passes and docs in the same commit.
- If messenger transport mode changes (`sync`/`async`), update docs and operational notes (worker requirements, failure transport).

## Decisions Needed From Maintainer
- Approve one canonical array style for query params everywhere (`symbol[]=...` or another single format).
- Define explicit public API boundary list for Contracts/Bundles to control BC decisions.
- Confirm target messenger mode roadmap (`sync` only vs planned `async`) and acceptance criteria for switching.
- Provide required end-to-end smoke scenario(s) for release checks (which events, which market, expected output).
- Confirm extraction roadmap for standalone packages (`SymfonyEventDiscovery`, `SymfonyDependencyDiscovery`, `SymfonyDefinitionFactory`).
