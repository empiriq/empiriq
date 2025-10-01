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
        balance:
            arguments:
                connector: '@connector'
                dispatcher: '@event_dispatcher'
                symbols: ['BNBUSDT']
```
