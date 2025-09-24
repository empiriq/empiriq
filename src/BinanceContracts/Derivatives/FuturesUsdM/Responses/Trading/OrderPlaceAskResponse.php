<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Trading;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\RateLimit;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Trading\Results\OrderPlaceAskResult;

readonly class OrderPlaceAskResponse
{
    /**
     * @param string $id
     * @param int $status
     * @param OrderPlaceAskResult $result
     * @param RateLimit[] $rateLimits
     */
    public function __construct(
        public string $id,
        public int $status,
        public OrderPlaceAskResult $result,
        public array $rateLimits = [],
    ) {
    }
}
