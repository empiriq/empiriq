<?php

namespace Empiriq\BinanceTradeBundle\Markets\Spot\Methods;

use Empiriq\BinanceContracts\Markets\Spot\Common\Permission;
use Empiriq\BinanceContracts\Markets\Spot\Requests\MarketData\Depth;
use Empiriq\BinanceContracts\Markets\Spot\Responses\MarketData\DepthResponse;
use React\Promise\PromiseInterface;

/**
 * Market data sender methods
 *
 * @link https://developers.binance.com/docs/binance-spot-api-docs/websocket-api/market-data-requests
 * @internal
 */
trait MarketDataMethods
{
    /**
     * @link https://developers.binance.com/docs/binance-spot-api-docs/websocket-api/market-data-requests#order-book
     * @param Depth $payload
     * @return PromiseInterface<DepthResponse>
     */
    public function depth(Depth $payload): PromiseInterface
    {
        return $this->ws->send(
            method: 'depth',
            permission: Permission::NONE,
            type: DepthResponse::class,
            payload: $payload,
        );
    }
}
