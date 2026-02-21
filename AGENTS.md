# AGENTS.md

## Scope
This file defines local project conventions for automated changes.

## Core Rules
- Keep backward compatibility for public bundle APIs unless explicitly requested.
- Prefer small compiler-pass based changes over runtime reflection hacks.
- Do not replace Symfony event subscribers/listeners used by app code.

## Event Subscription Format
- Use query-style event names: `binance.<market>.<scope>.<type>?symbol[]=btcusdt&symbol[]=ethusdt`.
- Treat each subscription key as a Symfony event name.
- Treat the same key as stream generation input during container build.

## Event Delivery Pipeline
- Producer (WS clients, Runner) publishes via `Empiriq\\Contracts\\Messaging\\EventPublisherInterface`.
- Publisher wraps payload into `Empiriq\\Contracts\\Messaging\\DomainEventMessage`.
- Messenger bus processes message (default transport: `sync://`).
- `DomainEventMessageHandler` forwards to Symfony `EventDispatcherInterface`.

## Documentation Sync
- When changing event flow, update `README.md`.
- When changing event flow, update `src/BinanceTradeBundle/README.md`.
- When changing event flow, update config snippets under `demo/config/packages/`.
