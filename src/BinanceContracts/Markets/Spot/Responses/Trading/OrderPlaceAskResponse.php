<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Responses\Trading;

use Empiriq\BinanceContracts\Markets\Spot\Common\RateLimit;
use Empiriq\BinanceContracts\Markets\Spot\Responses\Trading\Results\OrderPlaceAskResult;

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
