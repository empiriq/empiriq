<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Trading\Results;

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
