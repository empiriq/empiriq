Binance Manager Bundle
===

## Installation

```bash
composer require empiriq/binance-manager-bundle
```

Then register the bundle in your config/bundles.php:

```php
return [
    Empiriq\BinanceManagerBundle\EmpiriqBinanceManagerBundle::class => ['all' => true],
];
```

## Configuration

The bundle exposes a single root node: binance_manager.
You can configure the manager class, as well as registries (Spot, USD-M Futures, COIN-M Futures) and their repositories.

### Example Configuration

```yaml
# config/packages/binance_manager.yaml
binance_manager:
    futures_usd:
        registry_class: App\Custom\FuturesUsdMRegistry
        order:
            class: App\Repository\CustomOrderRepository
            arguments:
                http_client: '@my_custom_http_client'
                logger: '@monolog.logger.trading'
        balance:
            class: App\Repository\CustomBalanceRepository
        trade_history:
            class: App\Repository\CustomTradeHistoryRepository

    futures_coin:
        registry_class: App\Custom\FuturesCoinMRegistry
        position:
            class: App\Repository\CustomPositionRepository
            arguments:
                cache: '@app.cache'
        position_history:
            class: Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\PositionHistoryRepository
        transaction_history:
            class: Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\TransactionHistoryRepository
```
