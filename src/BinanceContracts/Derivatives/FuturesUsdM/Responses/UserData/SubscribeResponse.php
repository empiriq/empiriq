<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\UserData;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\RateLimit;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\UserData\Results\ListenKey;

readonly class SubscribeResponse
{
    /**
     * @param string $id
     * @param int $status
     * @param ListenKey $result
     * @param RateLimit[] $rateLimits
     */
    public function __construct(
        public string $id,
        public int $status,
        public ListenKey $result,
        public array $rateLimits = [],
    ) {
    }
}
