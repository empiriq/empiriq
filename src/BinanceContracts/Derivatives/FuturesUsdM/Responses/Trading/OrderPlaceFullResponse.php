<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Trading;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\RateLimit;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Trading\Results\OrderPlaceFullResult;

readonly class OrderPlaceFullResponse
{
    /**
     * @param string $id
     * @param int $status
     * @param OrderPlaceFullResult $result
     * @param RateLimit[] $rateLimits
     */
    public function __construct(
        public string $id,
        public int $status,
        public OrderPlaceFullResult $result,
        public array $rateLimits = [],
    ) {
    }
}
