<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Methods;

use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\Permission;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\General\PingResponse;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\General\TimeResponse;
use React\Promise\PromiseInterface;

/**
 * General sender methods
 *
 * @link
 * @internal
 */
trait GeneralMethods
{
    /**
     * todo doc not exist
     * @return PromiseInterface<PingResponse>
     * @link
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
     * todo doc not exist
     * @return PromiseInterface<TimeResponse>
     * @link
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
    //https://developers.binance.com/docs/derivatives/coin-margined-futures/market-data/rest-api
}
