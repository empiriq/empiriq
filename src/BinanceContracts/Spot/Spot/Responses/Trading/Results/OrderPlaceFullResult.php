<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Responses\Trading\Results;

use Empiriq\BinanceContracts\Spot\Spot\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Spot\Spot\Common\OrderSide;
use Empiriq\BinanceContracts\Spot\Spot\Common\OrderStatus;
use Empiriq\BinanceContracts\Spot\Spot\Common\OrderTimeInForce;
use Empiriq\BinanceContracts\Spot\Spot\Common\OrderType;

readonly class OrderPlaceFullResult
{
    /**
     * @param string $symbol
     * @param int $orderId
     * @param int $orderListId
     * @param string $clientOrderId
     * @param int $transactTime
     * @param float $price
     * @param float $origQty
     * @param float $executedQty
     * @param float $origQuoteOrderQty
     * @param float $cummulativeQuoteQty
     * @param OrderStatus $status
     * @param OrderTimeInForce $timeInForce
     * @param OrderType $type
     * @param OrderSide $side
     * @param int $workingTime
     * @param OrderPreventionMode $selfTradePreventionMode
     * @param OrderNewFill[] $fills
     */
    public function __construct(
        public string $symbol,
        public int $orderId,
        public int $orderListId,
        public string $clientOrderId,
        public int $transactTime,
        public float $price,
        public float $origQty,
        public float $executedQty,
        public float $origQuoteOrderQty,
        public float $cummulativeQuoteQty,
        public OrderStatus $status,
        public OrderTimeInForce $timeInForce,
        public OrderType $type,
        public OrderSide $side,
        public int $workingTime,
        public OrderPreventionMode $selfTradePreventionMode,
        public array $fills
    ) {
    }
}
