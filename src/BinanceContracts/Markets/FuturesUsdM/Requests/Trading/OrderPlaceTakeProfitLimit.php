<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Requests\Trading;

use Empiriq\BinanceContracts\Markets\FuturesUsdM\Common\OrderNewResponseType;
use Empiriq\BinanceContracts\Markets\FuturesUsdM\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Markets\FuturesUsdM\Common\OrderSide;
use Empiriq\BinanceContracts\Markets\FuturesUsdM\Common\OrderStopType;
use Empiriq\BinanceContracts\Markets\FuturesUsdM\Common\OrderTimeInForce;
use Empiriq\BinanceContracts\Markets\FuturesUsdM\Common\OrderType;

/**
 * Buy or sell "quantity" at the specified "price" or better.
 */
readonly class OrderPlaceTakeProfitLimit extends OrderPlace
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
            type: OrderType::TAKE_PROFIT_LIMIT,
            newClientOrderId: $newClientOrderId,
            strategyId: $strategyId,
            strategyType: $strategyType,
            selfTradePreventionMode: $selfTradePreventionMode,
            newOrderRespType: $newOrderRespType,
        );
    }
}
