<?php

namespace Empiriq\BinanceRealBundle\Markets\FuturesUm\Methods;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\Permission;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\Authentication\AccountStatusResponse;
use React\Promise\PromiseInterface;

use function React\Promise\resolve;

/**
 * Authentication sender methods
 *
 * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/websocket-api-general-info#websocket-api-authenticate-after-connection
 * @internal
 */
trait AuthenticationMethods
{
    /**
     * Log in with API key
     *
     * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/websocket-api-general-info#log-in-with-api-key-signed
     * @return PromiseInterface
     */
    public function sessionLogon(): PromiseInterface
    {
        return $this->ws->send(
            method: 'session.logon',
            permission: Permission::SIGNED,
            type: AccountStatusResponse::class,
            payload: [],
        );
    }

    /**
     * Log out of the session
     *
     * @link  https://developers.binance.com/docs/derivatives/usds-margined-futures/websocket-api-general-info#log-out-of-the-session
     * @return PromiseInterface
     */
    public function sessionLogout(): PromiseInterface
    {
        return resolve(null);
    }

    /**
     * Query session status
     *
     * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/websocket-api-general-info#query-session-status
     * @return PromiseInterface
     */
    public function sessionStatus(): PromiseInterface
    {
        return resolve(null);
    }
}
