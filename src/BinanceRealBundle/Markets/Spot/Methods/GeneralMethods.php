<?php

namespace Empiriq\BinanceRealBundle\Markets\Spot\Methods;

use Empiriq\BinanceContracts\Markets\Spot\Common\Permission;
use Empiriq\BinanceContracts\Markets\Spot\Responses\General\PingResponse;
use Empiriq\BinanceContracts\Markets\Spot\Responses\General\TimeResponse;
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
        return $this->ws->send(
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
        return $this->ws->send(
            method: 'time',
            permission: Permission::NONE,
            type: TimeResponse::class,
        );
    }

    //todo exchangeInfo
}
