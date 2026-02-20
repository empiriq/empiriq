<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCoinM\Responses\Trading\Results;

use Empiriq\BinanceContracts\Markets\FuturesCoinM\Common\OrderPreventionMode;
use Empiriq\BinanceContracts\Markets\FuturesCoinM\Common\OrderSide;
use Empiriq\BinanceContracts\Markets\FuturesCoinM\Common\OrderStatus;
use Empiriq\BinanceContracts\Markets\FuturesCoinM\Common\OrderTimeInForce;
use Empiriq\BinanceContracts\Markets\FuturesCoinM\Common\OrderType;

readonly class OrderPlaceResult
{
    public function __construct(
        public string $symbol,
        public int $orderId,
        public int $orderListId,
        public string $clientOrderId,
        public int $transactTime,
        public string $price,
        public float $origQty,
        public float $executedQty,
        public float $origQuoteOrderQty,
        public float $cummulativeQuoteQty,
        public OrderStatus $status,
        public OrderTimeInForce $timeInForce,
        public OrderType $type,
        public OrderSide $side,
        public int $workingTime,
        public OrderPreventionMode $selfTradePreventionMode
    ) {
    }
}
