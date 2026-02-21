<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Requests\Trading;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderSide;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderType;

/**
 * LIMIT_MAKER payload.
 *
 * Kept for compatibility with existing contracts.
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
    ) {
        parent::__construct(
            symbol: $symbol,
            side: $side,
            type: OrderType::LIMIT_MAKER,
            newClientOrderId: $newClientOrderId,
            strategyId: $strategyId,
            strategyType: $strategyType,
            selfTradePreventionMode: $selfTradePreventionMode,
        );
    }
}
