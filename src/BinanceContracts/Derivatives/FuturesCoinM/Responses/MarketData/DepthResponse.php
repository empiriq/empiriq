<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\MarketData;

use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\RateLimit;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\MarketData\Results\DepthResult;

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
