<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common;

use Empiriq\BinanceContracts\Spot\Spot\Common\RateLimitInterval;
use Empiriq\BinanceContracts\Spot\Spot\Common\RateLimitType;

readonly class RateLimit
{
    public function __construct(
        public RateLimitType $rateLimitType,
        public RateLimitInterval $interval,
        public int $intervalNum,
        public int $limit,
        public int $count,
    ) {
    }
}
