<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Requests\Trading;

use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderNewResponseType;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderQuantityType;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderSide;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\OrderType;

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
