<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Common;

use Empiriq\BinanceContracts\Markets\Spot\Common\RateLimitInterval;
use Empiriq\BinanceContracts\Markets\Spot\Common\RateLimitType;

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
