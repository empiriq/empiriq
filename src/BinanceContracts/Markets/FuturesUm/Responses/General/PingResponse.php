<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Responses\General;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\RateLimit;

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
