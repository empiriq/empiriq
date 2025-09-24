<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Requests\Trading;

use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderNewResponseType;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderSide;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderType;

/**
 * Buy or sell "quantity" at the specified "price" or better.
 */
readonly class OrderPlaceLimitMaker extends OrderPlace
{
    public function __construct(
        string $symbol,
        OrderSide $side,
        public float $price,
        public float $quantity,
        ?string $newClientOrderId,
        ?int $strategyId,
        ?int $strategyType,
        public ?float $icebergQty = null,
        ?OrderPreventionMode $selfTradePreventionMode = null,
        ?OrderNewResponseType $newOrderRespType = OrderNewResponseType::ACK,
    ) {
        parent::__construct(
            symbol: $symbol,
            side: $side,
            type: OrderType::LIMIT_MAKER,
            newClientOrderId: $newClientOrderId,
            strategyId: $strategyId,
            strategyType: $strategyType,
            selfTradePreventionMode: $selfTradePreventionMode,
            newOrderRespType: $newOrderRespType,
        );
    }
}
