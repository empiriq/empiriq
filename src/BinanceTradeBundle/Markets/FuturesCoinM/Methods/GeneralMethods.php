<?php

namespace Empiriq\BinanceTradeBundle\Markets\FuturesCoinM\Methods;

use Empiriq\BinanceContracts\Markets\FuturesCoinM\Common\Permission;
use Empiriq\BinanceContracts\Markets\FuturesCoinM\Responses\General\PingResponse;
use Empiriq\BinanceContracts\Markets\FuturesCoinM\Responses\General\TimeResponse;
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
        return $this->ws->send(
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
        return $this->ws->send(
            method: 'time',
            permission: Permission::NONE,
            type: TimeResponse::class,
        );
    }

    //todo exchangeInfo
    //https://developers.binance.com/docs/derivatives/coin-margined-futures/market-data/rest-api
}
