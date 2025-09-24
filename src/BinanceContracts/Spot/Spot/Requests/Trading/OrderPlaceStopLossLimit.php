<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Requests\Trading;

use Empiriq\BinanceContracts\Spot\Spot\Common\OrderNewResponseType;
use Empiriq\BinanceContracts\Spot\Spot\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Spot\Spot\Common\OrderSide;
use Empiriq\BinanceContracts\Spot\Spot\Common\OrderStopType;
use Empiriq\BinanceContracts\Spot\Spot\Common\OrderTimeInForce;
use Empiriq\BinanceContracts\Spot\Spot\Common\OrderType;

/**
 * Buy or sell "quantity" at the specified "price" or better.
 */
readonly class OrderPlaceStopLossLimit extends OrderPlace
{
    public ?float $stopPrice;

    public ?float $trailingDelta;

    public function __construct(
        string $symbol,
        OrderSide $side,
        public float $price,
        public float $quantity,
        public OrderTimeInForce $timeInForce,
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
            type: OrderType::STOP_LOSS_LIMIT,
            newClientOrderId: $newClientOrderId,
            strategyId: $strategyId,
            strategyType: $strategyType,
            selfTradePreventionMode: $selfTradePreventionMode,
            newOrderRespType: $newOrderRespType,
        );
    }
}
