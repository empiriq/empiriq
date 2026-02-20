<?php

namespace Empiriq\BinanceTradeBundle\Markets\FuturesUm\Methods;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\Permission;
use Empiriq\BinanceContracts\Markets\FuturesUm\Requests\Trading\OrderCancel;
use Empiriq\BinanceContracts\Markets\FuturesUm\Requests\Trading\OrderPlace;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\Trading\OrderCancelResponse;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\Trading\OrderPlaceResponse;
use React\Promise\PromiseInterface;

use function React\Promise\resolve;

/**
 * Trade endpoints for USD‑M Futures.
 * These methods are exposed via {@see \Empiriq\BinanceTradeBundle\Markets\FuturesUm\FuturesUm}
 * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/trade/websocket-api
 * @internal
 */
trait TradingMethods
{
    /**
     * Send in a new order.
     * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/trade/websocket-api
     * @return PromiseInterface<null>
     */
    public function orderPlace(OrderPlace $payload, int $recvWindow = 5000): PromiseInterface
    {
        return $this->ws->send(
            method: 'order.place',
            permission: Permission::TRADE,
            type: OrderPlaceResponse::class,
            payload: $payload,
            recvWindow: $recvWindow,
        );
    }

    /**
     * Order modify function, currently only LIMIT order modification is supported,
     * modified orders will be reordered in the match queue.
     * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/trade/websocket-api
     * @return PromiseInterface<null>
     */
    public function modifyOrder(): PromiseInterface
    {
        return resolve(null);
    }

    /**
     * Cancel an active order.
     * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/trade/websocket-api/Cancel-Order
     * @return PromiseInterface<OrderCancelResponse>
     */
    public function orderCancel(OrderCancel $payload, int $recvWindow = 5000): PromiseInterface
    {
        return $this->ws->send(
            method: 'order.cancel',
            permission: Permission::TRADE,
            type: OrderCancelResponse::class,
            payload: $payload,
            recvWindow: $recvWindow,
        );
    }

    /**
     * Check an order's status.
     * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/trade/websocket-api/Query-Order
     * @return PromiseInterface<null>
     */
    public function queryOrder(): PromiseInterface
    {
        return resolve(null);
    }

    /**
     * Get current position information(only symbol that has position or open orders will be returned).
     * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/trade/websocket-api/Position-Info-V2
     * @return PromiseInterface<null>
     */
    public function positionInformationV2(): PromiseInterface
    {
        return resolve(null);
    }

    /**
     * Get current position information.
     * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/trade/websocket-api/Position-Information
     * @return PromiseInterface<null>
     */
    public function positionInformation(): PromiseInterface
    {
        return resolve(null);
    }

    /**
     * Send in a new algo order.
     * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/trade/websocket-api/New-Algo-Order
     * @return PromiseInterface<null>
     */
    public function newAlgoOrder(): PromiseInterface
    {
        return resolve(null);
    }

    /**
     * Cancel an active algo order.
     * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/trade/websocket-api/Cancel-Algo-Order
     * @return PromiseInterface<null>
     */
    public function cancelAlgoOrder(): PromiseInterface
    {
        return resolve(null);
    }
}
