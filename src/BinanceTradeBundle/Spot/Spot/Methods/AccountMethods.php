<?php

namespace Empiriq\BinanceTradeBundle\Spot\Spot\Methods;

use Empiriq\BinanceContracts\Spot\Spot\Common\Permission;
use Empiriq\BinanceContracts\Spot\Spot\Responses\Account\AccountStatusResponse;
use React\Promise\PromiseInterface;

/**
 * Account sender methods
 *
 * @link https://developers.binance.com/docs/binance-spot-api-docs/websocket-api/account-requests
 * @internal
 */
trait AccountMethods
{
    /**
     * Account information
     *
     * @link https://developers.binance.com/docs/binance-spot-api-docs/websocket-api/account-requests#account-information-user_data
     * @return PromiseInterface<AccountStatusResponse>
     */
    public function accountStatus(): PromiseInterface //todo omitZeroBalances
    {
        return $this->websocketApi->send(
            method: 'account.status',
            permission: Permission::USER_DATA,
            type: AccountStatusResponse::class,
            payload: [],
        );
    }
}
