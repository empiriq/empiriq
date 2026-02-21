<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Requests\Trading;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderQuantityType;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderSide;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderType;

/**
 * MARKET order payload.
 *
 * Quantity is mapped to either `quantity` or `quoteOrderQty`
 * based on {@see OrderQuantityType}.
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
        );
    }
}
