<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\Permission;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\UserData\SubscribeResponse;
use React\Promise\PromiseInterface;

use function React\Promise\resolve;

/**
 * User Data Stream sender methods
 *
 * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/user-data-streams
 * @internal
 */
trait UserDataStreamMethods
{
    /**
     * Subscribe to User Data Stream
     * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/user-data-streams/Start-User-Data-Stream-Wsp
     * @return PromiseInterface<SubscribeResponse>
     */
    public function userDataStreamSubscribe(): PromiseInterface
    {
//        if (!$this->websocketApi->canLogon()) {
//            throw new \RuntimeException('!canLogon');
//        }
        return $this->websocketApi->send(
            method: 'userDataStream.start',
            permission: Permission::USER_STREAM,
            type: SubscribeResponse::class,
        );
    }

    /**
     * Unsubscribe from User Data Stream
     * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/user-data-streams/Close-User-Data-Stream-Wsp
     * @return PromiseInterface<SubscribeResponse>
     */
    public function userDataStreamUnsubscribe(): PromiseInterface
    {
        return $this->websocketApi->send(
            method: 'userDataStream.stop',
            permission: Permission::USER_STREAM,
            type: SubscribeResponse::class,
        );
    }

    /**
     * Start User Data Stream
     * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/user-data-streams/Start-User-Data-Stream
     * @return PromiseInterface
     */
    public function createListenKey(): PromiseInterface
    {
        return $this->restApi->send(
            method: 'POST',
            path: '/fapi/v1/listenKey',
            permission: Permission::USER_STREAM,
            type: SubscribeResponse::class,
        );
    }
    public function updateListenKey(string $listenKey): PromiseInterface
    {
        return $this->restApi->send(
            method: 'PUT',
            path: '/fapi/v1/listenKey',
            permission: Permission::USER_STREAM,
            type: SubscribeResponse::class,
            payload: ['listenKey' => $listenKey],
        );
    }
    public function deleteListenKey(string $listenKey): PromiseInterface
    {
        return $this->restApi->send(
            method: 'DELETE',
            path: '/fapi/v1/listenKey',
            permission: Permission::USER_STREAM,
            type: SubscribeResponse::class,
            payload: ['listenKey' => $listenKey],
        );
    }
}
