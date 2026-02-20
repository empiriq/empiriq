<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Responses\Trading;

use Empiriq\BinanceContracts\Markets\FuturesUsdM\Common\RateLimit;
use Empiriq\BinanceContracts\Markets\FuturesUsdM\Responses\Trading\Results\OrderPlaceResult;

readonly class OrderPlaceResponse
{
    /**
     * @param string $id
     * @param int $status
     * @param OrderPlaceResult $result
     * @param RateLimit[] $rateLimits
     */
    public function __construct(
        public string $id,
        public int $status,
        public OrderPlaceResult $result,
        public array $rateLimits = [],
    ) {
    }
}
