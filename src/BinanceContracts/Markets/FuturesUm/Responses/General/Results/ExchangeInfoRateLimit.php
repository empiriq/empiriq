<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Responses\General\Results;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\RateLimitInterval;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\RateLimitType;

readonly class ExchangeInfoRateLimit
{
    /**
     * @param RateLimitType $rateLimitType
     * @param RateLimitInterval $interval
     * @param int $intervalNum
     * @param int $limit
     */
    public function __construct(
        public RateLimitType $rateLimitType,
        public RateLimitInterval $interval,
        public int $intervalNum,
        public int $limit,
    ) {
    }
}
