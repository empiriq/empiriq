<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Requests\Trading;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderSide;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderStopType;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderType;

/**
 * TAKE_PROFIT_MARKET or TRAILING_STOP_MARKET payload.
 *
 * `stopValue` is mapped to `stopPrice` (fixed trigger) or `callbackRate`
 * (trailing trigger) based on {@see OrderStopType}.
 */
readonly class OrderPlaceTakeProfit extends OrderPlace
{
    public ?float $stopPrice;

    public ?float $callbackRate;

    public function __construct(
        string $symbol,
        OrderSide $side,
        public float $quantity,
        float $stopValue,
        OrderStopType $stopType,
        ?string $newClientOrderId,
        ?int $strategyId,
        ?int $strategyType,
        ?OrderPreventionMode $selfTradePreventionMode = null,
    ) {
        $this->stopPrice = $stopType === OrderStopType::FIX_PRICE ? $stopValue : null;
        $this->callbackRate = $stopType === OrderStopType::TRAILING_DELTA ? $stopValue : null;
        parent::__construct(
            symbol: $symbol,
            side: $side,
            type: $stopType === OrderStopType::FIX_PRICE
                ? OrderType::TAKE_PROFIT_MARKET
                : OrderType::TRAILING_STOP_MARKET,
            newClientOrderId: $newClientOrderId,
            strategyId: $strategyId,
            strategyType: $strategyType,
            selfTradePreventionMode: $selfTradePreventionMode,
        );
    }
}
