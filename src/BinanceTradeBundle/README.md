Binance Trade Bundle
===

A modular, event-driven library for real-time Binance trading with asynchronous communication and typed objects,
configurable
via [Dependency Injection](#dependency-injection) or
the [Symfony Bundle System](#symfony-bundle-system).

### Features

- **Non-blocking:** Asynchronous, promise-based requests powered
  by [react/promise](https://github.com/reactphp/promise).
- **Event-driven:** Exchange events are published directly into
  the [symfony/event-dispatcher](https://symfony.com/doc/current/components/event_dispatcher.html).
- **Typed:** Strongly-typed request, response, and event objects
  via [empiriq/binance-contracts](../../src/BinanceContracts).
- **Fast:** Uses WebSocket endpoints when available to ensure speed, and falls back to REST when not.

### Supported Markets

| Market Type    | Namespace                                                                                              | REST API | WebSocket API | WebSocket Streams |
|----------------|--------------------------------------------------------------------------------------------------------|----------|---------------|-------------------|
| Futures USD-M  | [`BinanceTradeBundle/Derivatives/FuturesUsdM`](../../src/BinanceTradeBundle/Derivatives/FuturesUsdM)   | ✅        | ✅             | ✅                 |
| Futures COIN-M | [`BinanceTradeBundle/Derivatives/FuturesCoinM`](../../src/BinanceTradeBundle/Derivatives/FuturesCoinM) | ✅        | ✅             | ✅                 |
| Options        | `BinanceTradeBundle/Derivatives/Options`                                                               | 🚧       | 🚧            | 🚧                |
| Futures Algo   | `BinanceTradeBundle/Derivatives/FuturesAlgo`                                                           | 🚧       | 🚧            | 🚧                |
| Spot           | [`BinanceTradeBundle/Spot/Spot`](../../src/BinanceTradeBundle/Spot/Spot)                               | ✅        | ✅             | ✅                 |
| Margin         | `BinanceTradeBundle/Spot/Margin`                                                                       | 🚧       | 🚧            | 🚧                |

# Installation

Install via [Composer](https://getcomposer.org/):

```bash
composer require empiriq/binance-trade-bundle
```

# Configuration

The library can be configured via [**Dependency Injection**](#dependency-injection) or the
[**Symfony Bundle System**](#symfony-bundle-system).

## Dependency Injection

In this section, we’ll configure the main entry point of the library — the [**Connector**](#connector) — step by step
using lower-level parts. This approach demonstrates how the library can be used in a modular way, assembling
only the parts you need.

### Signer

The **Signer** is responsible for signing requests to Binance’s authenticated endpoints (such as account info, orders,
or balances).

The library provides multiple [signer implementations](Common/Signers) for different authentication methods:

- **HmacSigner:** Standard HMAC symmetric signing using Secret Key issued by Binance.
- **Ed25519Signer:** Modern Ed25519 asymmetric signing. You generate your own key pair and upload the public key to
  Binance.
- **RsaSigner:** RSA-based asymmetric signing for institutional or high-security clients. Similar setup to Ed25519.
- **NullSigner:** Used for public endpoints that don’t require authentication.

Creating Binance API Credentials:

- [For Mainnet](https://www.binance.com/en/support/faq/detail/360002502072)
- [For Testnet](https://www.binance.com/en/support/faq/detail/ab78f9a1b8824cf0a106b4229c76496d)

```php
$signer = new Empiriq\BinanceTradeBundle\Common\Signers\HmacSigner(
    secretKey: 'xxxXxx0xXxxxXxx0xXxxxXxx0xXxxxXxx0xXxxxXxx0xXxxxXxx0xXxxxXxx0xXx'
);
```

### REST API

The `RestApi` client allows you to send HTTP requests to Binance REST endpoints.

Let’s create a `RestApi` instance configured for the Binance Futures Testnet. This example also demonstrates how to send
signed requests with an `apiKey` and `secretKey`:

```php
$restApi = new Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\RestApi(
    uri: 'https://testnet.binancefuture.com',
    apiKey: 'XxXXxXXX0XxXXxXXX0XxXXxXXX0XxXXxXXX0XxXXxXXX0XxXXxXXX0XxXXxXXX0x',
    signer: $signer,
);
```

Here’s an example of how to send a request to fetch your account balance:

```php
$restApi->send(
    method: 'GET',
    path: '/fapi/v3/balance',
    permission: \Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\Permission::USER_DATA,
    type: \Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Account\AccountBalanceResponse::class,
)->then(function (\Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Account\AccountBalanceResponse $data) {
    var_dump($data);
})->catch(function (\React\Http\Message\ResponseException $data) {
    var_dump($data);
});
```

<details>
<summary>Response Example</summary>
<pre>
<code>
class Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Account\AccountBalanceResponse#241 (4) {
  public readonly string $id =>
  string(16) "35d8cfe26a5618ca"
  public readonly int $status =>
  int(200)
  public readonly array $result =>
  array(7) {
    [0] =>
    class Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Account\Results\Balance#337 (9) {
      public readonly string $accountAlias =>
      string(14) "fWAufWmYfWAuTi"
      public readonly string $asset =>
      string(5) "FDUSD"
      public readonly float $balance =>
      double(0)
      public readonly float $crossWalletBalance =>
      double(0)
      public readonly float $crossUnPnl =>
      double(0)
      public readonly float $availableBalance =>
      double(0)
      public readonly float $maxWithdrawAmount =>
      double(0)
      public readonly bool $marginAvailable =>
      bool(true)
      public readonly string $updateTime =>
      string(1) "0"
    }
    [1] =>
    class Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Account\Results\Balance#325 (9) {
      public readonly string $accountAlias =>
      string(14) "fWAufWmYfWAuTi"
      public readonly string $asset =>
      string(5) "BFUSD"
      public readonly float $balance =>
      double(0)
      public readonly float $crossWalletBalance =>
      double(0)
      public readonly float $crossUnPnl =>
      double(0)
      public readonly float $availableBalance =>
      double(0)
      public readonly float $maxWithdrawAmount =>
      double(0)
      public readonly bool $marginAvailable =>
      bool(true)
      public readonly string $updateTime =>
      string(1) "0"
    }
    [2] =>
    class Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Account\Results\Balance#432 (9) {
      public readonly string $accountAlias =>
      string(14) "fWAufWmYfWAuTi"
      public readonly string $asset =>
      string(3) "BNB"
      public readonly float $balance =>
      double(0)
      public readonly float $crossWalletBalance =>
      double(0)
      public readonly float $crossUnPnl =>
      double(0)
      public readonly float $availableBalance =>
      double(0)
      public readonly float $maxWithdrawAmount =>
      double(0)
      public readonly bool $marginAvailable =>
      bool(true)
      public readonly string $updateTime =>
      string(1) "0"
    }
    [3] =>
    class Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Account\Results\Balance#324 (9) {
      public readonly string $accountAlias =>
      string(14) "fWAufWmYfWAuTi"
      public readonly string $asset =>
      string(3) "ETH"
      public readonly float $balance =>
      double(0)
      public readonly float $crossWalletBalance =>
      double(0)
      public readonly float $crossUnPnl =>
      double(0)
      public readonly float $availableBalance =>
      double(0)
      public readonly float $maxWithdrawAmount =>
      double(0)
      public readonly bool $marginAvailable =>
      bool(true)
      public readonly string $updateTime =>
      string(1) "0"
    }
    [4] =>
    class Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Account\Results\Balance#323 (9) {
      public readonly string $accountAlias =>
      string(14) "fWAufWmYfWAuTi"
      public readonly string $asset =>
      string(3) "BTC"
      public readonly float $balance =>
      double(0.01)
      public readonly float $crossWalletBalance =>
      double(0.01)
      public readonly float $crossUnPnl =>
      double(0)
      public readonly float $availableBalance =>
      double(0.01)
      public readonly float $maxWithdrawAmount =>
      double(0.01)
      public readonly bool $marginAvailable =>
      bool(true)
      public readonly string $updateTime =>
      string(13) "1760290452642"
    }
    [5] =>
    class Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Account\Results\Balance#314 (9) {
      public readonly string $accountAlias =>
      string(14) "fWAufWmYfWAuTi"
      public readonly string $asset =>
      string(4) "USDT"
      public readonly float $balance =>
      double(5000)
      public readonly float $crossWalletBalance =>
      double(5000)
      public readonly float $crossUnPnl =>
      double(0)
      public readonly float $availableBalance =>
      double(5000)
      public readonly float $maxWithdrawAmount =>
      double(5000)
      public readonly bool $marginAvailable =>
      bool(true)
      public readonly string $updateTime =>
      string(13) "1760290452631"
    }
    [6] =>
    class Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Account\Results\Balance#315 (9) {
      public readonly string $accountAlias =>
      string(14) "fWAufWmYfWAuTi"
      public readonly string $asset =>
      string(4) "USDC"
      public readonly float $balance =>
      double(5000)
      public readonly float $crossWalletBalance =>
      double(5000)
      public readonly float $crossUnPnl =>
      double(0)
      public readonly float $availableBalance =>
      double(5000)
      public readonly float $maxWithdrawAmount =>
      double(5000)
      public readonly bool $marginAvailable =>
      bool(true)
      public readonly string $updateTime =>
      string(13) "1760290452637"
    }
  }
  public readonly array $rateLimits =>
  array(0) {
  }
}
</code>
</pre>
</details>

### WebSocket API

The WebsocketApi client manages request/response calls through the Binance WebSocket API.
This enables real-time commands and data exchange without additional HTTP overhead.

```php
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WebSocketApi;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\Permission;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Account\AccountBalanceResponse;

$api = new WebSocketApi(
    uri: '',
    apiKey: '',
    signer:,
);
$api->connect()
    ->then(fn(WebSocketApi $api) => $api->send(
        method: 'v2/account.balance',
        permission: Permission::USER_DATA,
        type: AccountBalanceResponse::class,
    ))
    ->then(var_dump);
```

### Streams

**Streams** define the real-time data channels you want to subscribe to — such as `trade`, `ticker`, or `orderBook`.
They are connected through WebSocket and automatically publish events into Symfony’s EventDispatcher.

Example:

```php
new TradeStream(['BTCUSDT']);
```

This subscribes to live trade events for the BTCUSDT symbol.

### WebSocket Streams

The WebsocketStreams client connects to market data streams, such as live trades, tickers, and depth updates.
It continuously receives data and dispatches strongly-typed event objects to your application.

### Transports

**Transport** represents a specific Binance market (e.g., Futures USD-M, Futures COIN-M, Spot).
Each transport handles both REST and WebSocket communication for that market and defines the available **streams**.

```php
$connector->futuresUsdM()->ping();
$connector->futuresUsdM()->createOrder(...);
```

### Connector

The **Connector** is the main entry point of the library.
It coordinates all communication between your application and Binance.
Each connector instance manages one or more **transports** — for example, Futures USD-M or Spot.
You can call `$connector->futuresUsdM()` or `$connector->spot()` to access a specific market.

```php
$connector = new \Empiriq\BinanceTradeBundle\Connector(
    transports: [/*  */]
);
```

## Symfony Bundle System

Then register the bundle in your [config/bundles.php](../../demo/config/bundles.php):

```php
return [
    // ...
    Empiriq\BinanceTradeBundle\BinanceTradeBundle::class => ['all' => true],
];
```

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

# Usage

В разделе Configuration via Dependency Injection мы уже рассмотрели некоторые примеры использования частей библиотеки.
В этом разделе мы рассмотрим примеры использования полностью собранного коннектора

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
            websocketApi: new \Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WebSocketApi(),
            websocketStreams: new \Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WebSocketStreams(),
        )
    ]
);
```

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