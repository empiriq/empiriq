<?php

namespace Empiriq\BinanceTradeBundle\Spot\Spot\Methods;

use Empiriq\BinanceContracts\Spot\Spot\Common\OrderNewResponseType;
use Empiriq\BinanceContracts\Spot\Spot\Common\Permission;
use Empiriq\BinanceContracts\Spot\Spot\Requests\Trading\OrderAmendKeepPriority;
use Empiriq\BinanceContracts\Spot\Spot\Requests\Trading\OrderCancel;
use Empiriq\BinanceContracts\Spot\Spot\Requests\Trading\OrderCancelAll;
use Empiriq\BinanceContracts\Spot\Spot\Requests\Trading\OrderCancelReplace;
use Empiriq\BinanceContracts\Spot\Spot\Requests\Trading\OrderFindAll;
use Empiriq\BinanceContracts\Spot\Spot\Requests\Trading\OrderPlace;
use Empiriq\BinanceContracts\Spot\Spot\Responses\Trading\OrderCancelResponse;
use Empiriq\BinanceContracts\Spot\Spot\Responses\Trading\OrderFindAllResponse;
use Empiriq\BinanceContracts\Spot\Spot\Responses\Trading\OrderPlaceAskResponse;
use Empiriq\BinanceContracts\Spot\Spot\Responses\Trading\OrderPlaceFullResponse;
use Empiriq\BinanceContracts\Spot\Spot\Responses\Trading\OrderPlaceResponse;
use React\Promise\PromiseInterface;

/**
 * Trading sender methods
 *
 * @link https://developers.binance.com/docs/binance-spot-api-docs/websocket-api/trading-requests
 * @internal
 */
trait TradingMethods
{
    public function orderAmendKeepPriority(OrderAmendKeepPriority $payload, int $recvWindow = 5000): PromiseInterface
    {
        return $this->websocketApi->send(
            method: 'order.amend.keepPriority',
            permission: Permission::TRADE,
            type: OrderCancelResponse::class,
            payload: $payload,
            recvWindow: $recvWindow,
        );
    }

    public function orderCancel(OrderCancel $payload, int $recvWindow = 5000): PromiseInterface
    {
        return $this->websocketApi->send(
            method: 'order.cancel',
            permission: Permission::TRADE,
            type: OrderCancelResponse::class,
            payload: $payload,
            recvWindow: $recvWindow,
        );
    }

    public function orderCancelAll(OrderCancelAll $payload, int $recvWindow = 5000): PromiseInterface
    {
        return $this->websocketApi->send(
            method: 'openOrders.cancelAll',
            permission: Permission::TRADE,
            type: OrderCancelResponse::class,
            payload: $payload,
            recvWindow: $recvWindow,
        );
    }

    /**
     * @param OrderCancelReplace $payload
     * @param int $recvWindow
     * @return PromiseInterface<OrderCancelResponse>
     */
    public function orderCancelReplace(OrderCancelReplace $payload, int $recvWindow = 5000): PromiseInterface
    {
        return $this->websocketApi->send(
            method: 'order.cancelReplace',
            permission: Permission::TRADE,
            type: OrderCancelResponse::class,
            payload: $payload,
            recvWindow: $recvWindow,
        );
    }

    public function orderFindAll(OrderFindAll $payload, int $recvWindow = 5000): PromiseInterface
    {
        return $this->websocketApi->send(
            method: 'allOrders',
            permission: Permission::TRADE,
            type: OrderFindAllResponse::class,
            payload: $payload,
            recvWindow: $recvWindow,
        );
    }

    public function orderPlace(OrderPlace $payload, int $recvWindow = 5000): PromiseInterface
    {
        return $this->websocketApi->send(
            method: 'order.place',
            permission: Permission::TRADE,
            type: match ($payload->newOrderRespType) {
                OrderNewResponseType::ACK => OrderPlaceAskResponse::class,
                OrderNewResponseType::RESULT => OrderPlaceResponse::class,
                OrderNewResponseType::FULL => OrderPlaceFullResponse::class,
            },
            payload: $payload,
            recvWindow: $recvWindow,
        );
    }
}
