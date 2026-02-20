<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCoinM\Responses\Trading;

use Empiriq\BinanceContracts\Markets\FuturesCoinM\Responses\Trading\Results\OrderPlaceFullResult;
use Empiriq\BinanceContracts\Markets\Spot\Common\RateLimit;

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
