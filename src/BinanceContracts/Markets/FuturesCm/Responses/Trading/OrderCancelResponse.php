<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCm\Responses\Trading;

use Empiriq\BinanceContracts\Markets\FuturesCm\Common\RateLimit;
use Empiriq\BinanceContracts\Markets\FuturesCm\Responses\Trading\Results\OrderCancelResult;

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
