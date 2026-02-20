<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Responses\Trading;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\RateLimit;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\Trading\Results\OrderPlaceAskResult;

readonly class OrderPlaceAskResponse
{
    /**
     * @param string $id
     * @param int $status
     * @param OrderPlaceAskResult $result
     * @param RateLimit[] $rateLimits
     */
    public function __construct(
        public string $id,
        public int $status,
        public OrderPlaceAskResult $result,
        public array $rateLimits = [],
    ) {
    }
}
