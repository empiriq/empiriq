<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\Permission;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\General\PingResponse;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\General\TimeResponse;
use React\Promise\PromiseInterface;

/**
 * General sender methods
 *
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
        return $this->restApi->send(
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
        return $this->restApi->send(
            method: 'GET',
            path: '/fapi/v1/time',
            permission: Permission::NONE,
            type: TimeResponse::class,
        );
    }

    /**
     * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/market-data/rest-api/Exchange-Information
     * @return PromiseInterface<TimeResponse>
     */
    public function exchangeInfo(): PromiseInterface
    {
        return $this->restApi->send(
            method: 'GET',
            path: '/fapi/v1/exchangeInfo',
            permission: Permission::NONE,
            type: TimeResponse::class,
        );
    }
}
