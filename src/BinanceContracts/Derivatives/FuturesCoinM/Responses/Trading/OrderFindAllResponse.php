<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\Trading;

use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\RateLimit;

readonly class OrderFindAllResponse
{
    /**
     * @param string $id
     * @param int $status
     * @param array $result
     * @param RateLimit[] $rateLimits
     */
    public function __construct(
        public string $id,
        public int $status,
        public array $result,
        public array $rateLimits = [],
    ) {
    }
}
