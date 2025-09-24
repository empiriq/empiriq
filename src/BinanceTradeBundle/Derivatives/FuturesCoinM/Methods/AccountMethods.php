<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Methods;

use React\Promise\PromiseInterface;

use function React\Promise\resolve;

/**
 * Account sender methods
 *
 * @link https://developers.binance.com/docs/derivatives/coin-margined-futures/account/websocket-api/Account-Information
 * @internal
 */
trait AccountMethods
{
    /**
     * Account information
     *
     * @link https://developers.binance.com/docs/derivatives/coin-margined-futures/account/websocket-api/Account-Information#method
     */
    public function accountStatus(): PromiseInterface
    {
        return resolve(null);
    }
}
