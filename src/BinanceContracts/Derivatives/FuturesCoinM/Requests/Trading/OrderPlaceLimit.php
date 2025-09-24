<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Requests\Trading;

use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderNewResponseType;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderSide;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderTimeInForce;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderType;

/**
 * Buy or sell "quantity" at the specified "price" or better.
 */
readonly class OrderPlaceLimit extends OrderPlace
{
    public float $icebergQty;

    public function __construct(
        string $symbol,
        OrderSide $side,
        public float $price,
        public float $quantity,
        public OrderTimeInForce $timeInForce,
        ?string $newClientOrderId,
        ?int $strategyId,
        ?int $strategyType,
        ?float $icebergQty = null,
        ?OrderPreventionMode $selfTradePreventionMode = null,
        ?OrderNewResponseType $newOrderRespType = OrderNewResponseType::FULL,
    ) {
        $this->icebergQty = $timeInForce === OrderTimeInForce::GOOD_TILL_CANCEL ? $icebergQty : null;
        parent::__construct(
            symbol: $symbol,
            side: $side,
            type: OrderType::LIMIT,
            newClientOrderId: $newClientOrderId,
            strategyId: $strategyId,
            strategyType: $strategyType,
            selfTradePreventionMode: $selfTradePreventionMode,
            newOrderRespType: $newOrderRespType,
        );
    }
}
