<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Responses\Trading\Results;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderPriceMatchMode;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderSide;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderStatus;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderTimeInForce;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderType;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\OrderWorkingType;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\PositionSide;

readonly class OrderPlaceResult
{
    /**
     * Full RESULT payload for `order.place`.
     *
     * Numeric values are represented as strings by Binance.
     *
     * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/trade/websocket-api/New-Order
     */
    public function __construct(
        public int $orderId,
        public string $symbol,
        public OrderStatus $status,
        public string $clientOrderId,
        public string $price,
        public string $avgPrice,
        public string $origQty,
        public string $executedQty,
        public string $cumQty,
        public string $cumQuote,
        public OrderTimeInForce $timeInForce,
        public OrderType $type,
        public bool $reduceOnly,
        public bool $closePosition,
        public OrderSide $side,
        public PositionSide $positionSide,
        public string $stopPrice,
        public OrderWorkingType $workingType,
        public bool $priceProtect,
        public OrderType $origType,
        public OrderPriceMatchMode $priceMatch,
        public OrderPreventionMode $selfTradePreventionMode,
        public int $goodTillDate,
        public int $updateTime,
    ) {
    }
}
