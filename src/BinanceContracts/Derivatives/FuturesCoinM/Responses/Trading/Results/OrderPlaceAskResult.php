<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\Trading\Results;

readonly class OrderPlaceAskResult
{
    public function __construct(
        public string $symbol,
        public int $orderId,
        public int $orderListId,
        public string $clientOrderId,
        public int $transactTime,
    ) {
    }
}
