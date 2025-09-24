<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Responses\Trading;

use Empiriq\BinanceContracts\Spot\Spot\Common\RateLimit;
use Empiriq\BinanceContracts\Spot\Spot\Responses\Trading\Results\OrderPlaceResult;

readonly class OrderPlaceResponse
{
    /**
     * @param string $id
     * @param int $status
     * @param OrderPlaceResult $result
     * @param RateLimit[] $rateLimits
     */
    public function __construct(
        public string $id,
        public int $status,
        public OrderPlaceResult $result,
        public array $rateLimits = [],
    ) {
    }
}
