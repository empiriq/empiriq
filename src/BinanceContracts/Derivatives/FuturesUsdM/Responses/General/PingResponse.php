<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\General;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\RateLimit;

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
