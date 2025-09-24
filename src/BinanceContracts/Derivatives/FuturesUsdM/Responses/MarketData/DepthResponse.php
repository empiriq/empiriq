<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\MarketData;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\RateLimit;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\MarketData\Results\DepthResult;

readonly class DepthResponse
{
    /**
     * @param string $id
     * @param int $status
     * @param DepthResult $result
     * @param RateLimit[] $rateLimits
     */
    public function __construct(
        public string $id,
        public int $status,
        public DepthResult $result,
        public array $rateLimits = [],
    ) {
    }
}
