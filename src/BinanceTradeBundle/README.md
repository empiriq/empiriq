Binance Trade Bundle
===

A modular, event-driven library for real-time Binance trading with asynchronous communication and typed objects.

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

The bundle supports multiple Binance authentication mechanisms.
Each request requires an **API key** and a **signer**.

## Supported signers

* `hmac` — HMAC SHA256
* `ed25519` — Ed25519 private key
* `rsa` — RSA private key
* `null` — unsigned (public endpoints)

## YAML configuration

```yaml
# config/packages/binance_trade.yaml
binance_trade:
  environment: mainnet # mainnet | testnet
  
  api_key: '%env(BINANCE_API_KEY)%'

  signer:
    type: hmac # hmac | ed25519 | rsa | null

    # hmac only
    secret_key: '%env(BINANCE_SECRET_KEY)%'

    # ed25519 / rsa only
    private_key_path: '%env(resolve:BINANCE_PRIVATE_KEY_PATH)%'
    passphrase: '%env(BINANCE_PRIVATE_KEY_PASSPHRASE)%'

```

Only parameters relevant to the selected signer.type are required.

## Compile-time configuration

The bundle is further configured **during container build**. It scans registered
Symfony event subscribers and determines which Binance events your application
subscribes to. Based on those subscriptions, the bundle enables only the
corresponding streams to avoid unnecessary connections.

## Available event subscriptions

Use the event class names below in `getSubscribedEvents()` to enable the
corresponding streams.

Spot (market):
- `binance.spot.market.trade.btcusdt`
- `binance.spot.market.depth.btcusdt`

Spot (user):
- `binance.spot.user.balance_update`
- `binance.spot.user.outbound_account_position`
- `binance.spot.user.execution_report`
- `binance.spot.user.external_lock_update`

Futures USD-M (market):
- `binance.futures_usd.market.trade.btcusdt`
- `binance.futures_usd.market.depth.btcusdt`

Futures USD-M (user):
- `binance.futures_usd.user.balance_update`
- `binance.futures_usd.user.outbound_account_position`
- `binance.futures_usd.user.execution_report`
- `binance.futures_usd.user.external_lock_update`

Futures COIN-M (market):
- `binance.futures_coin.market.trade.btcusdt`
- `binance.futures_coin.market.depth.btcusdt`

Futures COIN-M (user):
- `binance.futures_coin.user.balance_update`
- `binance.futures_coin.user.outbound_account_position`
- `binance.futures_coin.user.execution_report`
- `binance.futures_coin.user.external_lock_update`

## Signer

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


### Transports

**Transport** represents a specific Binance market (e.g., Futures USD-M, Futures COIN-M, Spot).
Each transport handles both REST and WebSocket communication for that market and defines the available **streams**.

```php
$connector->futuresUsdM()->ping();
$connector->futuresUsdM()->createOrder(...);
```

# Listening to Events

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
