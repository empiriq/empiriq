<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCm\Responses\MarketData;

use Empiriq\BinanceContracts\Markets\FuturesCm\Common\RateLimit;
use Empiriq\BinanceContracts\Markets\FuturesCm\Responses\MarketData\Results\DepthResult;

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
