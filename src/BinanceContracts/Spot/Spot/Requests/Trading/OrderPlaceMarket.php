<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Requests\Trading;

use Empiriq\BinanceContracts\Spot\Spot\Common\OrderNewResponseType;
use Empiriq\BinanceContracts\Spot\Spot\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Spot\Spot\Common\OrderQuantityType;
use Empiriq\BinanceContracts\Spot\Spot\Common\OrderSide;
use Empiriq\BinanceContracts\Spot\Spot\Common\OrderType;

/**
 * Buy or sell "quantity" at the specified "price" or better.
 */
readonly class OrderPlaceMarket extends OrderPlace
{
    public ?float $quantity;

    public ?float $quoteOrderQty;

    public function __construct(
        string $symbol,
        OrderSide $side,
        float $quantity,
        OrderQuantityType $quantityType,
        ?string $newClientOrderId,
        ?int $strategyId,
        ?int $strategyType,
        ?OrderPreventionMode $selfTradePreventionMode = null,
        ?OrderNewResponseType $newOrderRespType = OrderNewResponseType::FULL,
    ) {
        $this->quantity = $quantityType === OrderQuantityType::BASE ? $quantity : null;
        $this->quoteOrderQty = $quantityType === OrderQuantityType::QUOTE ? $quantity : null;
        parent::__construct(
            symbol: $symbol,
            side: $side,
            type: OrderType::MARKET,
            newClientOrderId: $newClientOrderId,
            strategyId: $strategyId,
            strategyType: $strategyType,
            selfTradePreventionMode: $selfTradePreventionMode,
            newOrderRespType: $newOrderRespType,
        );
    }
}
