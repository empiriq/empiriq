<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Responses\MarketData;

use Empiriq\BinanceContracts\Markets\Spot\Common\RateLimit;
use Empiriq\BinanceContracts\Markets\Spot\Responses\MarketData\Results\DepthResult;

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
