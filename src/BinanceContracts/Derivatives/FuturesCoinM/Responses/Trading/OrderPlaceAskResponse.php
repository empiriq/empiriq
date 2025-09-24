<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\Trading;

use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\Trading\Results\OrderPlaceAskResult;
use Empiriq\BinanceContracts\Spot\Spot\Common\RateLimit;

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
