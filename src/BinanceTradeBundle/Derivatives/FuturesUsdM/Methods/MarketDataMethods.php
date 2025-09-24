<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Methods;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\Permission;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Requests\MarketData\Depth;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\MarketData\DepthResponse;
use React\Promise\PromiseInterface;

/**
 * Market data sender methods
 *
 * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/market-data/websocket-api
 * @internal
 */
trait MarketDataMethods
{
    /**
     * @param Depth $payload
     * @return PromiseInterface<DepthResponse>
     * @link
     */
    public function depth(Depth $payload): PromiseInterface
    {
        return $this->websocketApi->send(
            method: 'depth',
            permission: Permission::NONE,
            type: DepthResponse::class,
            payload: $payload,
        );
    }
}
