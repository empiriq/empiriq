<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Responses\Trading;

use Empiriq\BinanceContracts\Spot\Spot\Common\RateLimit;
use Empiriq\BinanceContracts\Spot\Spot\Responses\Trading\Results\OrderCancelResult;

readonly class OrderCancelResponse
{
    /**
     * @param string $id
     * @param int $status
     * @param OrderCancelResult $result
     * @param RateLimit[] $rateLimits
     */
    public function __construct(
        public string $id,
        public int $status,
        public OrderCancelResult $result,
        public array $rateLimits = [],
    ) {
    }
}
