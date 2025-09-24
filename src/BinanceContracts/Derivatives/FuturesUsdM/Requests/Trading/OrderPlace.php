<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Requests\Trading;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\OrderNewResponseType;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\OrderSide;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\OrderType;

/**
 * Place new order (TRADE)
 *
 * This adds 1 order to the EXCHANGE_MAX_ORDERS filter and the MAX_NUM_ORDERS filter.
 * @link https://github.com/binance/binance-spot-api-docs/blob/master/web-socket-api.md#place-new-order-trade
 */
readonly abstract class OrderPlace
{
    public function __construct(
        public string $symbol,
        public OrderSide $side,
        public OrderType $type,
        public ?string $newClientOrderId,
        public ?int $strategyId,
        public ?int $strategyType,
        public ?OrderPreventionMode $selfTradePreventionMode,
        public ?OrderNewResponseType $newOrderRespType,
    ) {
    }
}
