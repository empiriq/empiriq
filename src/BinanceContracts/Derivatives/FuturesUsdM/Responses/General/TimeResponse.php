<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\General;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\RateLimit;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\General\Results\TimeResult;

readonly class TimeResponse
{
    /**
     * @param string $id
     * @param int $status
     * @param TimeResult $result
     * @param RateLimit[] $rateLimits
     */
    public function __construct(
        public string $id,
        public int $status,
        public TimeResult $result,
        public array $rateLimits = [],
    ) {
    }
}
