<?php

namespace Empiriq\BinanceTradeBundle\Spot\Spot\Methods;

use Empiriq\BinanceContracts\Spot\Spot\Common\Permission;
use Empiriq\BinanceContracts\Spot\Spot\Responses\Account\AccountStatusResponse;
use React\Promise\PromiseInterface;

/**
 * Authentication sender methods
 *
 * @link https://developers.binance.com/docs/binance-spot-api-docs/websocket-api/authentication-requests
 * @internal
 */
trait AuthenticationMethods
{
    public function sessionLogon(): PromiseInterface
    {
        return $this->websocketApi->send(
            method: 'session.logon',
            permission: Permission::SIGNED,
            type: AccountStatusResponse::class,
            payload: [],
        );
    }

    public function sessionLogout(): PromiseInterface
    {
        return $this->websocketApi->send(
            method: 'session.logout',
            permission: Permission::NONE,
            type: AccountStatusResponse::class,
        );
    }

    public function sessionStatus(): PromiseInterface
    {
        return $this->websocketApi->send(
            method: 'session.status',
            permission: Permission::NONE,
            type: AccountStatusResponse::class,
        );
    }
}
