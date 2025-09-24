<?php

namespace Empiriq\BinanceTradeBundle\Spot\Spot\Methods;

use Empiriq\BinanceContracts\Spot\Spot\Common\Permission;
use Empiriq\BinanceContracts\Spot\Spot\Responses\UserData\SubscribeResponse;
use React\Promise\PromiseInterface;

/**
 * User Data Stream sender methods
 *
 * @link https://developers.binance.com/docs/binance-spot-api-docs/websocket-api/user-data-stream-requests
 * @internal
 */
trait UserDataStreamMethods
{
    /**
     * Subscribe to User Data Stream
     *
     * @link https://developers.binance.com/docs/binance-spot-api-docs/websocket-api/user-data-stream-requests#subscribe-to-user-data-stream-user_stream
     * @return PromiseInterface<SubscribeResponse>
     */
    public function userDataStreamSubscribe(): PromiseInterface
    {
        return $this->websocketApi->send(
            method: 'userDataStream.subscribe',
            permission: Permission::USER_STREAM,
            type: SubscribeResponse::class,
        );
    }

    /**
     * Unsubscribe from User Data Stream
     *
     * @link https://developers.binance.com/docs/binance-spot-api-docs/websocket-api/user-data-stream-requests#unsubscribe-from-user-data-stream
     * @return PromiseInterface<SubscribeResponse>
     */
    public function userDataStreamUnsubscribe(): PromiseInterface
    {
        return $this->websocketApi->send(
            method: 'userDataStream.unsubscribe',
            permission: Permission::NONE,
            type: SubscribeResponse::class,
        );
    }

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
