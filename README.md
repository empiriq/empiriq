Empiriq Framework
===

High-performance algorithmic trading PHP framework providing quantitative traders with the ability to backtest
portfolios of automated trading strategies on historical data with an event-driven engine, and also deploy those
same strategies live, with no code changes.

### Features

- **Non-blocking:** Asynchronous, promise-based requests powered
  by [react/promise](https://github.com/reactphp/promise).
- **Event-driven:** Exchange events are published directly into
  [symfony/messenger](https://symfony.com/doc/current/components/messenger.html) (sync transport by default)
  and then forwarded to [symfony/event-dispatcher](https://symfony.com/doc/current/components/event_dispatcher.html).
- **Typed:** Strongly-typed request, response, and event objects
  via [empiriq/binance-contracts](src/BinanceContracts).
- **Fast:** Uses WebSocket endpoints when available to ensure speed, and falls back to REST when not.

# Installation

```bash
composer require empiriq/empiriq
```

1.0.x-dev -> v1.0.0-alpha.0

## Similar projects

- https://github.com/nautechsystems
