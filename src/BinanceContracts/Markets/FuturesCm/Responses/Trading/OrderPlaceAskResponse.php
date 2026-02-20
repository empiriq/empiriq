<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCm\Responses\Trading;

use Empiriq\BinanceContracts\Markets\FuturesCm\Responses\Trading\Results\OrderPlaceAskResult;
use Empiriq\BinanceContracts\Markets\Spot\Common\RateLimit;

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
