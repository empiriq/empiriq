<?php

namespace Empiriq\BinanceTradeBundle\Markets\FuturesUm\Methods;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\Permission;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\General\ExchangeInfoResponse;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\General\PingResponse;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\General\TimeResponse;
use React\Promise\PromiseInterface;

/**
 * General REST endpoints for USD‑M Futures.
 * These methods are exposed via {@see \Empiriq\BinanceTradeBundle\Markets\FuturesUm\FuturesUm}
 * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/market-data/rest-api
 * @internal
 */
trait GeneralMethods
{
    /**
     * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/market-data/rest-api
     * @return PromiseInterface<PingResponse>
     */
    public function ping(): PromiseInterface
    {
        return $this->rest->send(
            method: 'GET',
            path: '/fapi/v1/ping',
            permission: Permission::NONE,
            type: PingResponse::class,
        );
    }

    /**
     * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/market-data/rest-api/Check-Server-Time
     * @return PromiseInterface<TimeResponse>
     */
    public function time(): PromiseInterface
    {
        return $this->rest->send(
            method: 'GET',
            path: '/fapi/v1/time',
            permission: Permission::NONE,
            type: TimeResponse::class,
        );
    }

    /**
     * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/market-data/rest-api/Exchange-Information
     * @return PromiseInterface<ExchangeInfoResponse>
     */
    public function exchangeInfo(): PromiseInterface
    {
        return $this->rest->send(
            method: 'GET',
            path: '/fapi/v1/exchangeInfo',
            permission: Permission::NONE,
            type: ExchangeInfoResponse::class,
        );
    }
}
