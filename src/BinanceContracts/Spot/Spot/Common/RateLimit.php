<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Common;

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
