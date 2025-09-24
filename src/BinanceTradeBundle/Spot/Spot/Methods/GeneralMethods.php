<?php

namespace Empiriq\BinanceTradeBundle\Spot\Spot\Methods;

use Empiriq\BinanceContracts\Spot\Spot\Common\Permission;
use Empiriq\BinanceContracts\Spot\Spot\Responses\General\PingResponse;
use Empiriq\BinanceContracts\Spot\Spot\Responses\General\TimeResponse;
use React\Promise\PromiseInterface;

/**
 * General sender methods
 *
 * @link https://developers.binance.com/docs/binance-spot-api-docs/websocket-api/general-requests
 * @internal
 */
trait GeneralMethods
{
    /**
     * @link https://developers.binance.com/docs/binance-spot-api-docs/websocket-api/general-requests#test-connectivity
     * @return PromiseInterface<PingResponse>
     */
    public function ping(): PromiseInterface
    {
        return $this->websocketApi->send(
            method: 'ping',
            permission: Permission::NONE,
            type: PingResponse::class,
        );
    }

    /**
     * @link https://developers.binance.com/docs/binance-spot-api-docs/websocket-api/general-requests#check-server-time
     * @return PromiseInterface<TimeResponse>
     */
    public function time(): PromiseInterface
    {
        return $this->websocketApi->send(
            method: 'time',
            permission: Permission::NONE,
            type: TimeResponse::class,
        );
    }

    //todo exchangeInfo
}
