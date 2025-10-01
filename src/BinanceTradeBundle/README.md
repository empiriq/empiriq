Binance Trade Bundle
===

Фичи:

1) Неблокирующие запросы на промисах react/promise
2) Публикация событий биржи в диспетчер symfony/event-dispatcher
3) Типизированные объекты запросов, ответов и событий empiriq/binance-contracts
4) Отправка запросов чарез вебсокет апи

Пример запуска на тесте

# Clients

# Transports

[Derivatives/FuturesCoinM](Derivatives/FuturesCoinM)

[Derivatives/FuturesUsdM](Derivatives/FuturesUsdM)

[Spot/Spot](Spot/Spot)

## Installation

```bash
composer require empiriq/binance-trade-bundle
```

Then register the bundle in your config/bundles.php:

```php
return [
    Empiriq\BinanceTradeBundle\BinanceTradeBundle::class => ['all' => true],
];
```