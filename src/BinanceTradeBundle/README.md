Binance Trade Bundle
===

A Symfony bundle and standalone library for trading on Binance via WebSocket and REST APIs.  
It provides asynchronous communication, typed objects, and event-driven architecture built for real-time trading
systems.

### Features

- **Non-blocking**: Asynchronous, promise-based requests powered
  by [react/promise](https://github.com/reactphp/promise).
- **Event-driven**: Exchange events are published directly into
  the [Symfony EventDispatcher](https://symfony.com/doc/current/components/event_dispatcher.html).
- **Typed**: Strongly-typed request, response, and event objects
  via [empiriq/binance-contracts](../../src/BinanceContracts).
- **WebSocket API**: Uses WebSocket endpoints when available for real-time speed, falling back to REST when not.

### Supported Markets

| Market Type    | Namespace                                                                           | REST API | WebSocket API | WebSocket Streams |
|----------------|-------------------------------------------------------------------------------------|----------|---------------|-------------------|
| Futures USD-M  | [`Derivatives/FuturesUsdM`](../../src/BinanceTradeBundle/Derivatives/FuturesUsdM)   | ✅        | ✅             | ✅                 |
| Futures COIN-M | [`Derivatives/FuturesCoinM`](../../src/BinanceTradeBundle/Derivatives/FuturesCoinM) | ✅        | ✅             | ✅                 |
| Spot           | [`Spot/Spot`](../../src/BinanceTradeBundle/Spot/Spot)                               | ✅        | ✅             | ✅                 |
| Futures Algo   | `Derivatives/FuturesAlgo`                                                           | 🚧       | 🚧            | 🚧                |
| Options        | `Derivatives/Options`                                                               | 🚧       | 🚧            | 🚧                |
| Margin         | `Spot/Margin`                                                                       | 🚧       | 🚧            | 🚧                |

## Concepts

### Connector

The **Connector** is the main entry point of the library.
It coordinates all communication between your application and Binance.
Each connector instance manages one or more **transports** — for example, Futures USD-M or Spot.
You can call `$connector->futuresUsdM()` or `$connector->spot()` to access a specific market.

### Transports

A **Transport** represents a specific Binance market (e.g., Futures USD-M, Futures COIN-M, Spot).
Each transport handles both REST and WebSocket communication for that market and defines the available **streams**.

Example:

```php
$connector->futuresUsdM()->ping();
$connector->futuresUsdM()->createOrder(...);
```

### Streams

**Streams** define the real-time data channels you want to subscribe to — such as `trade`, `ticker`, or `orderBook`.
They are connected through WebSocket and automatically publish events into Symfony’s EventDispatcher.

Example:

```php
new TradeStream(['BTCUSDT']);
```

This subscribes to live trade events for the BTCUSDT symbol.

### REST API

The RestApi client handles HTTP requests to Binance REST endpoints.
It’s used automatically when a WebSocket equivalent doesn’t exist (for example, for account management or order
creation).

### WebSocket API

The WebsocketApi client manages request/response calls through the Binance WebSocket API.
This enables real-time commands and data exchange without additional HTTP overhead.

### WebSocket Streams

The WebsocketStreams client connects to market data streams, such as live trades, tickers, and depth updates.
It continuously receives data and dispatches strongly-typed event objects to your application.

### Listening to Events

The bundle dispatches strongly-typed Binance events through Symfony’s EventDispatcher.  
You can subscribe to these events to react to real-time data updates such as trades, tickers, or order book changes.

Example:

```php
final class TradeEventListener implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            \Empiriq\BinanceContracts\Events\TradeEvent::class => 'onTrade',
        ];
    }

    public function onTrade(TradeEvent $event): void
    {
        printf("[TRADE] %s %.2f @ %.2f\n", $event->symbol, $event->quantity, $event->price);
    }
}
```

Register the listener as a Symfony service, and you’ll start receiving live trade events:

```yaml
# config/services.yaml
services:
    App\EventListener\TradeEventListener:
        tags: [ kernel.event_subscriber ]
```

Once your connector is running and subscribed to a trade stream,
the listener will automatically receive all matching events in real time.

## Installation

Install via [Composer](https://getcomposer.org/):

```bash
composer require empiriq/binance-trade-bundle
```

Then register the bundle in your config/bundles.php:

```php
return [
    Empiriq\BinanceTradeBundle\BinanceTradeBundle::class => ['all' => true],
];
```

## Configuration

The bundle exposes a single root node: binance_trade.
You can configure the connector.

### Example Configuration

```yaml
# config/packages/binance_trade.yaml
binance_trade:
    api_key: '%env(TEST_HMAC_SHA_256_API_KEY)%'
    signer:
        class: 'Empiriq\BinanceTradeBundle\Common\Signers\HmacSigner'
        arguments: [ '%env(TEST_HMAC_SHA_256_SECRET_KEY)%' ]
    #        class: 'Empiriq\BinanceTradeBundle\Common\Signers\Ed25519Signer'
    #        arguments: ['%env(EMPIRIQ_BINANCE_CONNECTOR_WEBSOCKET_SIGNER_PRIVATE_KEY_PATH)%']
    transports:
        futures_usd:
            rest_api_uri: 'https://testnet.binancefuture.com'
            websocket_api_uri: 'wss://testnet.binancefuture.com/ws-fapi/v1'
            websocket_market_streams_uri: 'wss://fstream.binancefuture.com/ws'
            streams:
                trade: [ [ 'BTCUSDT' ] ]

```

## Usage

```php
class PingService
{
    public function __construct(
        private \Empiriq\BinanceTradeBundle\Connector $connector
    ) {
    }

    public function ping(): void
    {
        $this->connector->futuresUsdM()->ping();
    }
}
```

```php
$connector = new \Empiriq\BinanceTradeBundle\Connector(
    transports: [
        new \Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\FuturesUsdMTransport(
            streams: [
                new \Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Streams\TradeStream(['BTCUSDT'])
            ],
            restApi: new \Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\RestApi(),
            websocketApi: new \Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WebsocketApi(),
            websocketStreams: new \Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WebsocketStreams(),
        )
    ],
    logger: new \Psr\Log\NullLogger()
);
```
