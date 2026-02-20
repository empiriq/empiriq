<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\General\Results;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\RateLimitInterval;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\RateLimitType;

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
