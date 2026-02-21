# AGENTS.md

## Scope
Local instructions for automated code changes in this repository.

## Project Map
- Runtime entrypoints: `demo/bin/real`, `demo/bin/paper`, `demo/bin/back` (boot `App\Kernel`, resolve `Empiriq\Contracts\Runner`).
- Core runtime contracts: `src/Contracts`.
- Market integration and DI compiler passes: `src/BinanceRealBundle`.
- Event and dependency discovery utilities: `src/SymfonyEventDiscovery`, `src/SymfonyDependencyDiscovery`.
- Tick generation bundle: `src/TickBundle`.
- Demo app wiring and env defaults: `demo/config`.

## Core Engineering Rules
- Preserve backward compatibility for classes/methods marked as public API (`@api`) unless explicitly requested.
- Prefer compile-time DI assembly (compiler passes + definitions) over runtime reflection/config mutation.
- Keep event subscribers/listeners in application code intact; infrastructure should adapt around them.
- Keep event names stable and parseable via URL components (`path + query`).

## Event Subscription Contract
- Binance event format: `binance.<market>.<scope>.<type>?symbol[]=btcusdt&symbol[]=ethusdt`.
- Ticker event format: `tick?interval=1.5`.
- `path` defines event kind; `query` defines generation/filter parameters.
- The same string is used in two roles:
- as Symfony subscription key (`getSubscribedEvents()` / listener event name)
- as container-build instruction for stream/clock registration
- Selector routing matches by event `path` and query values (array and comma-separated values are supported).

## Event Delivery Pipeline
- Producers (WS clients, `Runner`) publish through `Empiriq\Contracts\Messaging\EventPublisherInterface`.
- Publisher wraps payload into `Empiriq\Contracts\Messaging\DomainEventMessage`.
- Messenger bus handles the message (demo default transport: `sync://`).
- `DomainEventMessageHandler` forwards into Symfony `EventDispatcherInterface`.

## Market API Command Architecture
- Application code should call market APIs through Messenger command messages, not via direct calls to `Spot`, `FuturesUm`, `FuturesCm` methods.
- Command envelopes live in `src/BinanceRealBundle/Common/Messaging`:
- `SpotWsApiCommandMessage`, `SpotRestApiCommandMessage`
- `FuturesUmWsApiCommandMessage`, `FuturesUmRestApiCommandMessage`
- `FuturesCmWsApiCommandMessage`, `FuturesCmRestApiCommandMessage`
- Command messages intentionally have no generic `context` field.
- Command handlers are registered in `MarketBuildPass` only for markets that are actually built/enabled.

## Request DTO Contracts For Commands
- Request DTOs in `src/BinanceContracts/Markets/*/Requests` are command payloads.
- WS requests must implement market-specific WS marker interfaces:
- `SpotWsRequestInterface`
- `FuturesUmWsRequestInterface`
- `FuturesCmWsRequestInterface`
- These interfaces extend `WsRequestMetaInterface` and each DTO must provide:
- `wsMethod(): string`
- `permission(): PermissionInterface`
- `responseType(): class-string`
- REST marker interfaces exist and must be used for new REST command DTOs:
- `SpotRestRequestInterface`
- `FuturesUmRestRequestInterface`
- `FuturesCmRestRequestInterface`
- They extend `RestRequestMetaInterface` and require:
- `httpMethod(): string`
- `path(): string`
- `permission(): PermissionInterface`
- `responseType(): class-string`

## Command Calling Pattern
- Inject `Symfony\Component\Messenger\MessageBusInterface` and dispatch typed command messages.
- In `sync` mode, read `HandledStamp` result and `await()` returned `PromiseInterface` when needed.
- Keep market facades (`Spot`, `FuturesUm`, `FuturesCm`) for runtime lifecycle and stream orchestration (`run/shutdown`), not as primary app API surface.

## Adding New API Method (Command-First)
- Add/update request DTO in `BinanceContracts/Markets/<Market>/Requests/...`.
- Implement the correct market/API marker interface and metadata methods.
- Ensure `permission()` and `responseType()` match official Binance docs.
- For polymorphic responses, pin a single deterministic shape in DTO metadata (or explicit match logic in DTO).
- Reuse existing message+handler pair for that market/API; no new handler needed unless introducing a new market/API channel.

## API Method Playbook
- Use official Binance endpoint docs as the single source of truth for request/response shape.
- For each method, verify: WS/REST method name, permission level, required params, conditional params, and defaults.
- Keep enum values protocol-exact (string values must match Binance docs).
- For `order.place`-like methods, prefer one pinned full typed response DTO strategy.
- If endpoint supports multiple response shapes, pin request mode to the richest stable shape in request DTO defaults.
- Keep `PromiseInterface<T>` phpdoc aligned with real method return type.
- For Binance decimal fields in response DTOs, prefer `string` types unless endpoint guarantees numeric JSON.
- Preserve BC where practical: add enum values instead of replacing; keep legacy aliases with explicit comments.
- Avoid spot/futures DTO cross-pollination: validate each market type independently.
- Validate DTO constructors for nullability to avoid runtime `TypeError` on optional fields.

## API Change Checklist
- Update contracts DTOs (`Requests`, `Responses`, `Common enums`) first, then bundle methods.
- Re-check serializer normalization of request DTOs for expected outgoing payload keys.
- Re-check deserialization types against example payloads from docs.
- Update bundle README API reference when method semantics or params change.

## Quality Gates
- Style: `vendor/bin/phpcs --standard=PSR12 src`
- Static analysis: `vendor/bin/psalm --config=etc/psalm.xml --show-info=true --no-progress`
- Tests: `vendor/bin/phpunit --configuration=etc/phpunit.xml --cache-directory=var`
- CI reference: `.github/workflows/quality.yml`

## Change Checklist
- If event flow changes, update:
- `README.md`
- `src/BinanceRealBundle/README.md`
- relevant files in `demo/config/packages/`
- If event naming grammar changes, update parser points in compiler passes and docs in the same commit.
- If messenger transport mode changes (`sync`/`async`), update docs and operational notes (worker requirements, failure transport).

## Decisions Needed From Maintainer
- Approve one canonical array style for query params everywhere (`symbol[]=...` or another single format).
- Define explicit public API boundary list for Contracts/Bundles to control BC decisions.
- Confirm target messenger mode roadmap (`sync` only vs planned `async`) and acceptance criteria for switching.
- Provide required end-to-end smoke scenario(s) for release checks (which events, which market, expected output).
- Confirm extraction roadmap for standalone packages (`SymfonyEventDiscovery`, `SymfonyDependencyDiscovery`, `SymfonyDefinitionFactory`).
