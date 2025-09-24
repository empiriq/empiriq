<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\Trading;

use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\RateLimit;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\Trading\Results\OrderCancelResult;

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
