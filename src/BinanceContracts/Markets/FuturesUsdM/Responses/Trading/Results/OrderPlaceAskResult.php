<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Responses\Trading\Results;

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
