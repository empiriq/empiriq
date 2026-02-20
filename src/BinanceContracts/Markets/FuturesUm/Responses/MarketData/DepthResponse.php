<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Responses\MarketData;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\RateLimit;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\MarketData\Results\DepthResult;

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
