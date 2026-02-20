<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Requests\Trading;

use Empiriq\BinanceContracts\Markets\Spot\Common\OrderNewResponseType;
use Empiriq\BinanceContracts\Markets\Spot\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Markets\Spot\Common\OrderSide;
use Empiriq\BinanceContracts\Markets\Spot\Common\OrderStopType;
use Empiriq\BinanceContracts\Markets\Spot\Common\OrderType;

/**
 * Buy or sell "quantity" at the specified "price" or better.
 */
readonly class OrderPlaceTakeProfit extends OrderPlace
{
    public ?float $stopPrice;

    public ?float $trailingDelta;

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
        ?OrderNewResponseType $newOrderRespType = OrderNewResponseType::ACK,
    ) {
        $this->stopPrice = $stopType === OrderStopType::FIX_PRICE ? $stopValue : null;
        $this->trailingDelta = $stopType === OrderStopType::TRAILING_DELTA ? $stopValue : null;
        parent::__construct(
            symbol: $symbol,
            side: $side,
            type: OrderType::TAKE_PROFIT,
            newClientOrderId: $newClientOrderId,
            strategyId: $strategyId,
            strategyType: $strategyType,
            selfTradePreventionMode: $selfTradePreventionMode,
            newOrderRespType: $newOrderRespType,
        );
    }
}
