<?php

namespace Empiriq\BinanceRealBundle\Markets\FuturesCm\Methods;

use Empiriq\BinanceContracts\Markets\FuturesCm\Responses\MarketStream\GetPropertyResponse;
use Empiriq\BinanceContracts\Markets\FuturesCm\Responses\MarketStream\ListSubscriptionsResponse;
use Empiriq\BinanceContracts\Markets\FuturesCm\Responses\MarketStream\SetPropertyResponse;
use Empiriq\BinanceContracts\Markets\FuturesCm\Responses\MarketStream\SubscribeResponse;
use Empiriq\BinanceContracts\Markets\Spot\Common\Permission;
use React\Promise\PromiseInterface;

/**
 * @see
 */
trait MarketStreamMethods
{
    /**
     * @param array $streams like ["btcusdt@aggTrade", "btcusdt@depth"]
     * @return PromiseInterface
     */
    public function subscribe(array $streams): PromiseInterface
    {
        return $this->subscriptions->send(
            method: 'SUBSCRIBE',
            permission: Permission::NONE,
            type: SubscribeResponse::class,
            payload: $streams,
        );
    }

    /**
     * @param array $streams
     * @return PromiseInterface
     */
    public function unsubscribe(array $streams): PromiseInterface
    {
        return $this->subscriptions->send(
            method: 'UNSUBSCRIBE',
            permission: Permission::NONE,
            type: SubscribeResponse::class,
            payload: $streams,
        );
    }

    /**
     * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/websocket-market-streams/Live-Subscribing-Unsubscribing-to-streams#listing-subscriptions
     * @return PromiseInterface
     */
    public function listSubscriptions(): PromiseInterface
    {
        return $this->subscriptions->send(
            method: 'LIST_SUBSCRIPTIONS',
            permission: Permission::NONE,
            type: ListSubscriptionsResponse::class,
        );
    }

    /**
     * @param array $property
     * @return PromiseInterface
     */
    public function setProperty(array $property): PromiseInterface
    {
        return $this->subscriptions->send(
            method: 'SET_PROPERTY',
            permission: Permission::NONE,
            type: SetPropertyResponse::class,
            payload: $property,
        );
    }

    /**
     * @param array $property
     * @return PromiseInterface
     */
    public function getProperty(array $property): PromiseInterface
    {
        return $this->subscriptions->send(
            method: 'GET_PROPERTY',
            permission: Permission::NONE,
            type: GetPropertyResponse::class,
            payload: $property,
        );
    }
}
