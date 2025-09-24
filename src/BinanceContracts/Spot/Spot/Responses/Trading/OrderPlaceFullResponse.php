<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Responses\Trading;

use Empiriq\BinanceContracts\Spot\Spot\Common\RateLimit;
use Empiriq\BinanceContracts\Spot\Spot\Responses\Trading\Results\OrderPlaceFullResult;

readonly class OrderPlaceFullResponse
{
    /**
     * @param string $id
     * @param int $status
     * @param OrderPlaceFullResult $result
     * @param RateLimit[] $rateLimits
     */
    public function __construct(
        public string $id,
        public int $status,
        public OrderPlaceFullResult $result,
        public array $rateLimits = [],
    ) {
    }
}
