<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\Permission;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Account\AccountBalanceResponse;
use React\Promise\PromiseInterface;

/**
 * Account sender methods
 *
 * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/account/websocket-api/Account-Information-V2
 * @internal
 */
trait AccountMethods
{
    /**
     * Account balance info
     *
     * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/account/websocket-api
     */
    public function accountBalanceV2(): PromiseInterface
    {
        return $this->websocketApi->send(
            method: 'v2/account.balance',
            permission: Permission::USER_DATA,
            type: AccountBalanceResponse::class,
        );
    }
}
