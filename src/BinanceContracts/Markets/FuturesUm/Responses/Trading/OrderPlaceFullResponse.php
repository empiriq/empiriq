<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Responses\Trading;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\RateLimit;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\Trading\Results\OrderPlaceFullResult;

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
