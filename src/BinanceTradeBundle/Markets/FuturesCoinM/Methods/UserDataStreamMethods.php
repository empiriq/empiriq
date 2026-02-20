<?php

namespace Empiriq\BinanceTradeBundle\Markets\FuturesCoinM\Methods;

use Empiriq\BinanceContracts\Markets\FuturesCoinM\Common\Permission;
use Empiriq\BinanceContracts\Markets\FuturesCoinM\Responses\UserData\SubscribeResponse;
use React\Promise\PromiseInterface;

use function React\Promise\resolve;

/**
 * User Data Stream sender methods
 *
 * @link https://developers.binance.com/docs/derivatives/coin-margined-futures/user-data-streams
 * @internal
 */
trait UserDataStreamMethods
{
    /**
     * Subscribe to User Data Stream
     * @return PromiseInterface<SubscribeResponse>
     */
    public function userDataStreamSubscribe(): PromiseInterface
    {
        return resolve(null);
    }

    /**
     * Unsubscribe from User Data Stream
     * @return PromiseInterface<SubscribeResponse>
     */
    public function userDataStreamUnsubscribe(): PromiseInterface
    {
        return resolve(null);
    }

    public function createListenKey(): PromiseInterface
    {
        return $this->rest->send(
            method: 'POST',
            path: '/fapi/v1/listenKey',
            permission: Permission::USER_STREAM,
            type: SubscribeResponse::class,
        );
    }

    public function updateListenKey(string $listenKey): PromiseInterface
    {
        return $this->rest->send(
            method: 'PUT',
            path: '/fapi/v1/listenKey',
            permission: Permission::USER_STREAM,
            type: SubscribeResponse::class,
            payload: ['listenKey' => $listenKey],
        );
    }

    public function deleteListenKey(string $listenKey): PromiseInterface
    {
        return $this->rest->send(
            method: 'DELETE',
            path: '/fapi/v1/listenKey',
            permission: Permission::USER_STREAM,
            type: SubscribeResponse::class,
            payload: ['listenKey' => $listenKey],
        );
    }
}
