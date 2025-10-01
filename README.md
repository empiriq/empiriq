Empiriq Framework
===

## Introduction

High-performance algorithmic trading PHP framework providing quantitative traders with the ability to backtest 
portfolios of automated trading strategies on historical data with an event-driven engine, and also deploy those 
same strategies live, with no code changes.

## Features

- **Non-blocking**: Asynchronous, promise-based requests powered by [react/promise](https://github.com/reactphp/promise).
- **Event-driven**: Exchange events are published directly into the [Symfony EventDispatcher](https://symfony.com/doc/current/components/event_dispatcher.html).
- **Typed**: Strongly-typed entities and event objects via [empiriq/contracts](src/Contracts).
- **WebSocket-native**: Requests and responses flow through the Binance WebSocket API for efficient real-time communication.

## Installation

```bash
composer require empiriq/empiriq
```

1.0.x-dev -> v1.0.0-alpha.0

## Similar projects

- https://github.com/nautechsystems