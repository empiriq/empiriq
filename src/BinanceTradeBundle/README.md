# Binance Trade Bundle

## Overview
Binance Trade Bundle is a modular, event-driven library for real-time Binance trading with asynchronous communication and typed objects. It is non-blocking (React promises), publishes events through Symfony Messenger (sync transport by default) and then forwards them to Symfony EventDispatcher, uses typed contracts, and prefers WebSocket streams when available.

## Installation
### Requirements
- PHP >= 8.4
- Symfony >= 7.3 (Framework, DI, Config)
- ext-openssl
- ext-pcntl

### Install
```bash
composer require empiriq/binance-trade-bundle
```

Enable the bundle:
```php
return [
    // ...
    Empiriq\BinanceTradeBundle\BinanceTradeBundle::class => ['all' => true],
];
```

### Configuration
The bundle supports multiple Binance authentication mechanisms. Each request requires an API key and a signer.

Supported signers:
- `hmac` — HMAC SHA256
- `ed25519` — Ed25519 private key
- `rsa` — RSA private key
- `null` — null (public endpoints)

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

Creating Binance API Credentials:
- [For Mainnet](https://www.binance.com/en/support/faq/detail/360002502072)
- [For Testnet](https://www.binance.com/en/support/faq/detail/ab78f9a1b8824cf0a106b4229c76496d)

## General Usage Principles
During container build, the bundle scans Symfony event subscribers and registers only the streams that are actually requested by your event names. The same subscription names are used as instructions for stream generation.

Event names use a query-style format for parameters:
- `binance.<market>.<scope>.<type>?symbol[]=btcusdt&symbol[]=ethusdt`
- `symbol` accepts multiple comma-separated values.
- User events do not require `symbol`.

To enable streams, register an EventSubscriber and return the event names in `getSubscribedEvents()`.

Event delivery pipeline:
- WebSocket payload -> typed event object
- typed event -> `DomainEventMessage` on Messenger bus
- Messenger handler -> Symfony EventDispatcher (`dispatch($event, $eventName)`)

Default transport in demo setup is synchronous (`sync://`), so subscribers are called in-process.

All market classes expose async methods that return React promises. Inject the market class you need and call its methods directly.

Methods and events marked as **public** do not require a signer. Set `signer.type: null` for public-only access.

## API Reference

### Spot
Events:
- `binance.spot.market.trade?symbol[]=btcusdt` [public] [TradeEvent.php](../../src/BinanceContracts/Spot/Spot/Events/Market/TradeEvent.php) Trade updates
- `binance.spot.market.depth?symbol[]=btcusdt` [public] [DepthEvent.php](../../src/BinanceContracts/Spot/Spot/Events/Market/DepthEvent.php) Order book updates
- `binance.spot.user.balance_update` [auth] [BalanceUpdateEvent.php](../../src/BinanceContracts/Spot/Spot/Events/User/BalanceUpdateEvent.php) Balance update
- `binance.spot.user.outbound_account_position` [auth] [OutboundAccountPositionEvent.php](../../src/BinanceContracts/Spot/Spot/Events/User/OutboundAccountPositionEvent.php) Account position update
- `binance.spot.user.execution_report` [auth] [ExecutionReportEvent.php](../../src/BinanceContracts/Spot/Spot/Events/User/ExecutionReportEvent.php) Order execution report
- `binance.spot.user.external_lock_update` [auth] [ExternalLockUpdateEvent.php](../../src/BinanceContracts/Spot/Spot/Events/User/ExternalLockUpdateEvent.php) External lock update

Methods:
- `ping()` [public] [WS API] [GeneralMethods.php#L22](Spot/Spot/Methods/GeneralMethods.php#L22) Connectivity
- `time()` [public] [WS API] [GeneralMethods.php#L35](Spot/Spot/Methods/GeneralMethods.php#L35) Server time
- `sessionLogon()` [auth] [WS API] [AuthenticationMethods.php#L17](Spot/Spot/Methods/AuthenticationMethods.php#L17) Session logon
- `sessionLogout()` [auth] [WS API] [AuthenticationMethods.php#L27](Spot/Spot/Methods/AuthenticationMethods.php#L27) Session logout
- `sessionStatus()` [auth] [WS API] [AuthenticationMethods.php#L36](Spot/Spot/Methods/AuthenticationMethods.php#L36) Session status
- `depth(...)` [public] [WS API] [MarketDataMethods.php#L23](Spot/Spot/Methods/MarketDataMethods.php#L23) Order book snapshot
- `accountStatus()` [auth] [WS API] [AccountMethods.php#L23](Spot/Spot/Methods/AccountMethods.php#L23) Account info
- `orderAmendKeepPriority(...)` [auth] [WS API] [TradingMethods.php#L28](Spot/Spot/Methods/TradingMethods.php#L28) Amend order (keep priority)
- `orderCancel(...)` [auth] [WS API] [TradingMethods.php#L39](Spot/Spot/Methods/TradingMethods.php#L39) Cancel order
- `orderCancelAll(...)` [auth] [WS API] [TradingMethods.php#L50](Spot/Spot/Methods/TradingMethods.php#L50) Cancel all orders
- `orderCancelReplace(...)` [auth] [WS API] [TradingMethods.php#L66](Spot/Spot/Methods/TradingMethods.php#L66) Cancel/replace order
- `orderFindAll(...)` [auth] [WS API] [TradingMethods.php#L77](Spot/Spot/Methods/TradingMethods.php#L77) List orders
- `orderPlace(...)` [auth] [WS API] [TradingMethods.php#L88](Spot/Spot/Methods/TradingMethods.php#L88) Place order
- `userDataStreamSubscribe()` [auth] [WS API] [UserDataStreamMethods.php#L23](Spot/Spot/Methods/UserDataStreamMethods.php#L23) User stream subscribe
- `userDataStreamUnsubscribe()` [auth] [WS API] [UserDataStreamMethods.php#L38](Spot/Spot/Methods/UserDataStreamMethods.php#L38) User stream unsubscribe
- `createListenKey()` [auth] [REST] [UserDataStreamMethods.php#L47](Spot/Spot/Methods/UserDataStreamMethods.php#L47) Create listen key
- `updateListenKey(...)` [auth] [REST] [UserDataStreamMethods.php#L57](Spot/Spot/Methods/UserDataStreamMethods.php#L57) Update listen key
- `deleteListenKey(...)` [auth] [REST] [UserDataStreamMethods.php#L68](Spot/Spot/Methods/UserDataStreamMethods.php#L68) Delete listen key
- `subscribe(...)` [public] [WS streams] [MarketStreamMethods.php#L22](Spot/Spot/Methods/MarketStreamMethods.php#L22) Subscribe streams
- `unsubscribe(...)` [public] [WS streams] [MarketStreamMethods.php#L37](Spot/Spot/Methods/MarketStreamMethods.php#L37) Unsubscribe streams
- `listSubscriptions()` [public] [WS streams] [MarketStreamMethods.php#L51](Spot/Spot/Methods/MarketStreamMethods.php#L51) List subscriptions
- `setProperty(...)` [public] [WS streams] [MarketStreamMethods.php#L65](Spot/Spot/Methods/MarketStreamMethods.php#L65) Set property
- `getProperty(...)` [public] [WS streams] [MarketStreamMethods.php#L80](Spot/Spot/Methods/MarketStreamMethods.php#L80) Get property

### Futures USD-M
Events:
- `binance.futures_usd.market.trade?symbol[]=btcusdt` [public] [TradeEvent.php](../../src/BinanceContracts/Derivatives/FuturesUsdM/Events/Market/TradeEvent.php) Trade updates
- `binance.futures_usd.market.depth?symbol[]=btcusdt` [public] [DepthEvent.php](../../src/BinanceContracts/Derivatives/FuturesUsdM/Events/Market/DepthEvent.php) Order book updates
- `binance.futures_usd.user.account_update` [auth] [AccountUpdateEvent.php](../../src/BinanceContracts/Derivatives/FuturesUsdM/Events/User/AccountUpdateEvent.php) Account update
- `binance.futures_usd.user.order_trade_update` [auth] [OrderTradeUpdateEvent.php](../../src/BinanceContracts/Derivatives/FuturesUsdM/Events/User/OrderTradeUpdateEvent.php) Order/trade update
- `binance.futures_usd.user.margin_call` [auth] [MarginCallEvent.php](../../src/BinanceContracts/Derivatives/FuturesUsdM/Events/User/MarginCallEvent.php) Margin call
- `binance.futures_usd.user.trade_lite` [auth] [TradeLiteEvent.php](../../src/BinanceContracts/Derivatives/FuturesUsdM/Events/User/TradeLiteEvent.php) Trade lite update

Methods:
- `ping()` [public] [REST] [GeneralMethods.php#L22](Derivatives/FuturesUsdM/Methods/GeneralMethods.php#L22) Connectivity
- `time()` [public] [REST] [GeneralMethods.php#L36](Derivatives/FuturesUsdM/Methods/GeneralMethods.php#L36) Server time
- `exchangeInfo()` [public] [REST] [GeneralMethods.php#L50](Derivatives/FuturesUsdM/Methods/GeneralMethods.php#L50) Exchange info
- `sessionLogon()` [auth] [WS API] [AuthenticationMethods.php#L25](Derivatives/FuturesUsdM/Methods/AuthenticationMethods.php#L25) Session logon
- `sessionLogout()` [auth] [WS API] [AuthenticationMethods.php#L41](Derivatives/FuturesUsdM/Methods/AuthenticationMethods.php#L41) Session logout
- `sessionStatus()` [auth] [WS API] [AuthenticationMethods.php#L52](Derivatives/FuturesUsdM/Methods/AuthenticationMethods.php#L52) Session status
- `depth(...)` [public] [WS API] [MarketDataMethods.php#L23](Derivatives/FuturesUsdM/Methods/MarketDataMethods.php#L23) Order book snapshot
- `accountBalanceV2()` [auth] [WS API] [AccountMethods.php#L22](Derivatives/FuturesUsdM/Methods/AccountMethods.php#L22) Account balance
- `userDataStreamSubscribe()` [auth] [WS API] [UserDataStreamMethods.php#L22](Derivatives/FuturesUsdM/Methods/UserDataStreamMethods.php#L22) User stream start
- `userDataStreamUnsubscribe()` [auth] [WS API] [UserDataStreamMethods.php#L39](Derivatives/FuturesUsdM/Methods/UserDataStreamMethods.php#L39) User stream stop
- `createListenKey()` [auth] [REST] [UserDataStreamMethods.php#L53](Derivatives/FuturesUsdM/Methods/UserDataStreamMethods.php#L53) Create listen key
- `updateListenKey(...)` [auth] [REST] [UserDataStreamMethods.php#L63](Derivatives/FuturesUsdM/Methods/UserDataStreamMethods.php#L63) Update listen key
- `deleteListenKey(...)` [auth] [REST] [UserDataStreamMethods.php#L74](Derivatives/FuturesUsdM/Methods/UserDataStreamMethods.php#L74) Delete listen key
- `subscribe(...)` [public] [WS streams] [MarketStreamMethods.php#L22](Derivatives/FuturesUsdM/Methods/MarketStreamMethods.php#L22) Subscribe streams
- `unsubscribe(...)` [public] [WS streams] [MarketStreamMethods.php#L36](Derivatives/FuturesUsdM/Methods/MarketStreamMethods.php#L36) Unsubscribe streams
- `listSubscriptions()` [public] [WS streams] [MarketStreamMethods.php#L50](Derivatives/FuturesUsdM/Methods/MarketStreamMethods.php#L50) List subscriptions
- `setProperty(...)` [public] [WS streams] [MarketStreamMethods.php#L63](Derivatives/FuturesUsdM/Methods/MarketStreamMethods.php#L63) Set property
- `getProperty(...)` [public] [WS streams] [MarketStreamMethods.php#L77](Derivatives/FuturesUsdM/Methods/MarketStreamMethods.php#L77) Get property

### Futures COIN-M
Events:
- `binance.futures_coin.market.trade?symbol[]=btcusdt` [public] [TradeEvent.php](../../src/BinanceContracts/Derivatives/FuturesCoinM/Events/Market/TradeEvent.php) Trade updates
- `binance.futures_coin.market.depth?symbol[]=btcusdt` [public] [DepthEvent.php](../../src/BinanceContracts/Derivatives/FuturesCoinM/Events/Market/DepthEvent.php) Order book updates
- `binance.futures_coin.user.balance_update` [auth] [BalanceUpdateEvent.php](../../src/BinanceContracts/Derivatives/FuturesCoinM/Events/User/BalanceUpdateEvent.php) Balance update
- `binance.futures_coin.user.outbound_account_position` [auth] [OutboundAccountPositionEvent.php](../../src/BinanceContracts/Derivatives/FuturesCoinM/Events/User/OutboundAccountPositionEvent.php) Account position update
- `binance.futures_coin.user.execution_report` [auth] [ExecutionReportEvent.php](../../src/BinanceContracts/Derivatives/FuturesCoinM/Events/User/ExecutionReportEvent.php) Order execution report
- `binance.futures_coin.user.external_lock_update` [auth] [ExternalLockUpdateEvent.php](../../src/BinanceContracts/Derivatives/FuturesCoinM/Events/User/ExternalLockUpdateEvent.php) External lock update

Methods:
- `ping()` [public] [WS API] [GeneralMethods.php#L23](Derivatives/FuturesCoinM/Methods/GeneralMethods.php#L23) Connectivity
- `time()` [public] [WS API] [GeneralMethods.php#L37](Derivatives/FuturesCoinM/Methods/GeneralMethods.php#L37) Server time
- `sessionLogon()` [auth] [WS API] [AuthenticationMethods.php#L18](Derivatives/FuturesCoinM/Methods/AuthenticationMethods.php#L18) Session logon
- `sessionLogout()` [auth] [WS API] [AuthenticationMethods.php#L28](Derivatives/FuturesCoinM/Methods/AuthenticationMethods.php#L28) Session logout
- `sessionStatus()` [auth] [WS API] [AuthenticationMethods.php#L33](Derivatives/FuturesCoinM/Methods/AuthenticationMethods.php#L33) Session status
- `accountStatus()` [auth] [WS API] [AccountMethods.php#L22](Derivatives/FuturesCoinM/Methods/AccountMethods.php#L22) Account info (stub)
- `userDataStreamSubscribe()` [auth] [WS API] [UserDataStreamMethods.php#L23](Derivatives/FuturesCoinM/Methods/UserDataStreamMethods.php#L23) User stream subscribe (stub)
- `userDataStreamUnsubscribe()` [auth] [WS API] [UserDataStreamMethods.php#L32](Derivatives/FuturesCoinM/Methods/UserDataStreamMethods.php#L32) User stream unsubscribe (stub)
- `createListenKey()` [auth] [REST] [UserDataStreamMethods.php#L37](Derivatives/FuturesCoinM/Methods/UserDataStreamMethods.php#L37) Create listen key
- `updateListenKey(...)` [auth] [REST] [UserDataStreamMethods.php#L47](Derivatives/FuturesCoinM/Methods/UserDataStreamMethods.php#L47) Update listen key
- `deleteListenKey(...)` [auth] [REST] [UserDataStreamMethods.php#L58](Derivatives/FuturesCoinM/Methods/UserDataStreamMethods.php#L58) Delete listen key
- `subscribe(...)` [public] [WS streams] [MarketStreamMethods.php#L21](Derivatives/FuturesCoinM/Methods/MarketStreamMethods.php#L21) Subscribe streams
- `unsubscribe(...)` [public] [WS streams] [MarketStreamMethods.php#L35](Derivatives/FuturesCoinM/Methods/MarketStreamMethods.php#L35) Unsubscribe streams
- `listSubscriptions()` [public] [WS streams] [MarketStreamMethods.php#L49](Derivatives/FuturesCoinM/Methods/MarketStreamMethods.php#L49) List subscriptions
- `setProperty(...)` [public] [WS streams] [MarketStreamMethods.php#L62](Derivatives/FuturesCoinM/Methods/MarketStreamMethods.php#L62) Set property
- `getProperty(...)` [public] [WS streams] [MarketStreamMethods.php#L76](Derivatives/FuturesCoinM/Methods/MarketStreamMethods.php#L76) Get property

## Troubleshooting / FAQ
- If no streams are registered, verify event names exactly match the documented format.
- Use lowercase symbols in event names; they are normalized internally.
- User events require a valid signer and API key.
