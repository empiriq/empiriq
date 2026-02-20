<?php

namespace Empiriq\BinanceTradeBundle\Markets\FuturesCm\Methods;

use Empiriq\BinanceContracts\Markets\FuturesCm\Common\Permission;
use Empiriq\BinanceContracts\Markets\FuturesCm\Responses\Account\AccountStatusResponse;
use React\Promise\PromiseInterface;

use function React\Promise\resolve;

/**
 * Authentication sender methods
 *
 * @internal
 */
trait AuthenticationMethods
{
    public function sessionLogon(): PromiseInterface
    {
        return $this->ws->send(
            method: 'session.logon',
            permission: Permission::SIGNED,
            type: AccountStatusResponse::class,
            payload: [],
        );
    }

    public function sessionLogout(): PromiseInterface
    {
        return resolve(null);
    }

    public function sessionStatus(): PromiseInterface
    {
        return resolve(null);
    }
}
