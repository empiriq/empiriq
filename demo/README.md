Empiriq Demo Application
===

This repository demonstrates the development of a trading application using the **Avellaneda–Stoikov** market making
strategy on Bitcoin (BTCUSDT) via Binance Futures.

The project is intended for educational and experimental purposes and operates exclusively with Binance Demo (testnet)
access.

Demo environment: https://demo.binance.com/en/futures/BTCUSDT

INSTALLATION
---

```shell
composer create-project --prefer-dist empiriq/demo empiriq-demo
```

```shell
cp empiriq-demo/.env.dist empiriq-demo/.env
```

Edit the `.env` file and provide your Binance Demo API credentials.

API Credentials
---

You can generate API keys for the Binance Demo environment here:

https://demo.binance.com/en/my/settings/api-management

Running the Application
---

The application supports multiple runtime modes:

```shell
docker compose up
```
Runs the strategy in backtesting mode.

```shell
docker compose up
```
Runs the strategy in paper trading mode (simulated orders).

```shell
docker compose up
```
Runs the strategy in live demo trading mode on Binance Futures testnet.

Disclaimer
---

This project is provided as-is and is intended for learning and experimentation only.
It is not financial advice and must not be used with real funds.
