<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Responses\Trading;

use Empiriq\BinanceContracts\Markets\Spot\Common\RateLimit;
use Empiriq\BinanceContracts\Markets\Spot\Responses\Trading\Results\OrderPlaceFullResult;

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
