<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Requests\Trading;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderSide;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderTimeInForce;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderType;

/**
 * LIMIT order payload.
 *
 * Requires `price`, `quantity` and `timeInForce`.
 */
readonly class OrderPlaceLimit extends OrderPlace
{
    public ?float $icebergQty;

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
        );
    }
}
