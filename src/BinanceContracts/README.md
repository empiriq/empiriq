Binance Contracts
===

This package provides strongly-typed **request**, **response**, and **event** objects for the Binance API.  
It helps you work with Binance endpoints in a safe and predictable way.

### Supported Markets

| Market Type    | Namespace                                                                                          | Request objects | Response objects | Event objects |
|----------------|----------------------------------------------------------------------------------------------------|-----------------|------------------|---------------|
| Futures USD-M  | [`BinanceContracts/Derivatives/FuturesUsdM`](../../src/BinanceContracts/Derivatives/FuturesUsdM)   | ✅               | ✅                | ✅             |
| Futures COIN-M | [`BinanceContracts/Derivatives/FuturesCoinM`](../../src/BinanceContracts/Derivatives/FuturesCoinM) | ✅               | ✅                | ✅             |
| Options        | `BinanceContracts/Derivatives/Options`                                                             | 🚧              | 🚧               | 🚧            |
| Futures Algo   | `BinanceContracts/Derivatives/FuturesAlgo`                                                         | 🚧              | 🚧               | 🚧            |
| Spot           | [`BinanceContracts/Spot/Spot`](../../src/BinanceContracts/Spot/Spot)                               | ✅               | ✅                | ✅             |
| Margin         | `BinanceContracts/Spot/Margin`                                                                     | 🚧              | 🚧               | 🚧            |

# Installation

Install via [Composer](https://getcomposer.org/):

```bash
composer require empiriq/binance-contracts
```
