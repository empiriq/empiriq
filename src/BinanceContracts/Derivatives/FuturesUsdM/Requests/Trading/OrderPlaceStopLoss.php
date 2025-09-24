<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Requests\Trading;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\OrderNewResponseType;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\OrderSide;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\OrderStopType;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\OrderType;

/**
 * Buy or sell "quantity" at the specified "price" or better.
 */
readonly class OrderPlaceStopLoss extends OrderPlace
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
            type: OrderType::STOP_LOSS,
            newClientOrderId: $newClientOrderId,
            strategyId: $strategyId,
            strategyType: $strategyType,
            selfTradePreventionMode: $selfTradePreventionMode,
            newOrderRespType: $newOrderRespType,
        );
    }
}
