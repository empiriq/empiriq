<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Requests\Trading;

/**
 * Cancel all open orders on a symbol. This includes orders that are part of an order list.
 * @link https://github.com/binance/binance-spot-api-docs/blob/master/web-socket-api.md#cancel-open-orders-trade
 */
readonly class OrderCancelAll
{
    public function __construct(
        public string $symbol,
    ) {
    }
}
