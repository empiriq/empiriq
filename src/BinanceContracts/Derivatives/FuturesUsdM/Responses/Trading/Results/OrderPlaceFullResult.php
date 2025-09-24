<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Trading\Results;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\OrderSide;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\OrderStatus;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\OrderTimeInForce;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\OrderType;

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
