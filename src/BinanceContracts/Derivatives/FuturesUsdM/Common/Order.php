<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common;

/**
 * Represents an order update event from Binance Futures (USDⓂ).
 *
 * This DTO is populated from WebSocket stream events of type ORDER_TRADE_UPDATE.
 */
readonly class Order
{
    /**
     * @param string $symbol Symbol, e.g. "BTCUSDT"
     * @param OrderSide $side BUY or SELL
     * @param int $id Order ID
     * @param string $clientId Client order ID
     * @param OrderType $type Order type, e.g. LIMIT, MARKET
     * @param OrderTimeInForce $timeInForce Time in force, e.g. GTC
     * @param float $originalQuantity Original order quantity
     * @param float $originalPrice Original order price
     * @param float $averagePrice Average filled price
     * @param float $stopPrice Stop price
     * @param OrderExecutionType $executionType Execution type, e.g. NEW, TRADE
     * @param OrderStatus $status Current order status
     * @param float $lastFilledQuantity Last filled quantity
     * @param float $cumulativeFilledQuantity Cumulative filled quantity
     * @param float $lastFilledPrice Last filled price
     * @param string|null $commissionAsset Commission asset
     * @param float|null $commissionAmount Commission amount
     * @param int $tradeTime Trade time (ms since epoch)
     * @param int $tradeId Trade ID
     * @param float $bidNotional Bids notional
     * @param float $askNotional Asks notional
     * @param bool $isMaker True if this trade is maker side
     * @param bool $reduceOnly True if reduce-only order
     * @param OrderWorkingType $workingType Stop price working type
     * @param OrderType $originalOrderType Original order type
     * @param PositionSide $positionSide Position side: BOTH, LONG, SHORT
     * @param bool $closePosition True if close-all order
     * @param float|null $activationPrice Activation price (for trailing stop orders)
     * @param float|null $callbackRate Callback rate (for trailing stop orders)
     * @param bool $priceProtect Price protection flag
     * @param float $realizedProfit Realized profit
     * @param OrderPreventionMode $preventionMode STP (self-trade prevention) mode
     * @param OrderPriceMatchMode $priceMatchMode Price match mode
     * @param int $gtdCancelTime GTD cancel time (if applicable)
     */
    public function __construct(
        public string $symbol,
        public OrderSide $side,
        public int $id,
        public string $clientId,
        public OrderType $type,
        public OrderTimeInForce $timeInForce,
        public float $originalQuantity,
        public float $originalPrice,
        public float $averagePrice,
        public float $stopPrice,
        public OrderExecutionType $executionType,
        public OrderStatus $status,
        public float $lastFilledQuantity,
        public float $cumulativeFilledQuantity,
        public float $lastFilledPrice,
        public ?string $commissionAsset,
        public ?float $commissionAmount,
        public int $tradeTime,
        public int $tradeId,
        public float $bidNotional,
        public float $askNotional,
        public bool $isMaker,
        public bool $reduceOnly,
        public OrderWorkingType $workingType,
        public OrderType $originalOrderType,
        public PositionSide $positionSide,
        public bool $closePosition,
        public ?float $activationPrice,
        public ?float $callbackRate,
        public bool $priceProtect,
        public float $realizedProfit,
        public OrderPreventionMode $preventionMode,
        public OrderPriceMatchMode $priceMatchMode,
        public int $gtdCancelTime,
    ) {
    }
}
