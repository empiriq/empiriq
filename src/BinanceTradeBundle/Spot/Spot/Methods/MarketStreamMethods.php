<?php

namespace Empiriq\BinanceTradeBundle\Spot\Spot\Methods;

use Empiriq\BinanceContracts\Spot\Spot\Common\Permission;
use Empiriq\BinanceContracts\Spot\Spot\Responses\MarketStream\GetPropertyResponse;
use Empiriq\BinanceContracts\Spot\Spot\Responses\MarketStream\ListSubscriptionsResponse;
use Empiriq\BinanceContracts\Spot\Spot\Responses\MarketStream\SetPropertyResponse;
use Empiriq\BinanceContracts\Spot\Spot\Responses\MarketStream\SubscribeResponse;
use React\Promise\PromiseInterface;

/**
 * @see https://developers.binance.com/docs/binance-spot-api-docs/web-socket-streams#live-subscribingunsubscribing-to-streams
 */
trait MarketStreamMethods
{
    /**
     * @see https://developers.binance.com/docs/binance-spot-api-docs/web-socket-streams#live-subscribingunsubscribing-to-streams
     * @param array $streams like ["btcusdt@aggTrade", "btcusdt@depth"]
     * @return PromiseInterface
     */
    public function subscribe(array $streams): PromiseInterface
    {
        return $this->websocketStreams->send(
            method: 'SUBSCRIBE',
            permission: Permission::NONE,
            type: SubscribeResponse::class,
            payload: $streams,
        );
    }

    /**
     * @see https://developers.binance.com/docs/binance-spot-api-docs/web-socket-streams#unsubscribe-to-a-stream
     * @param array $streams like ["btcusdt@aggTrade", "btcusdt@depth"]
     * @return PromiseInterface
     */
    public function unsubscribe(array $streams): PromiseInterface
    {
        return $this->websocketStreams->send(
            method: 'UNSUBSCRIBE',
            permission: Permission::NONE,
            type: SubscribeResponse::class,
            payload: $streams,
        );
    }

    /**
     * @see https://developers.binance.com/docs/binance-spot-api-docs/web-socket-streams#listing-subscriptions
     * @return PromiseInterface
     */
    public function listSubscriptions(): PromiseInterface
    {
        return $this->websocketStreams->send(
            method: 'LIST_SUBSCRIPTIONS',
            permission: Permission::NONE,
            type: ListSubscriptionsResponse::class,
        );
    }

    /**
     * @see https://developers.binance.com/docs/binance-spot-api-docs/web-socket-streams#setting-properties
     * @param array $property like ["combined", true]
     * @return PromiseInterface
     */
    public function setProperty(array $property): PromiseInterface
    {
        return $this->websocketStreams->send(
            method: 'SET_PROPERTY',
            permission: Permission::NONE,
            type: SetPropertyResponse::class,
            payload: $property,
        );
    }

    /**
     * @see https://developers.binance.com/docs/binance-spot-api-docs/web-socket-streams#retrieving-properties
     * @param array $property like ["combined"]
     * @return PromiseInterface
     */
    public function getProperty(array $property): PromiseInterface
    {
        return $this->websocketStreams->send(
            method: 'GET_PROPERTY',
            permission: Permission::NONE,
            type: GetPropertyResponse::class,
            payload: $property,
        );
    }
}
