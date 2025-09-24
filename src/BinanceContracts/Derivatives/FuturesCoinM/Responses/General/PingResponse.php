<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\General;

use Empiriq\BinanceContracts\Spot\Spot\Common\RateLimit;

readonly class PingResponse
{
    /**
     * @param string $id
     * @param int $status
     * @param RateLimit[] $rateLimits
     */
    public function __construct(
        public string $id,
        public int $status,
        public array $rateLimits = [],
    ) {
    }
}
