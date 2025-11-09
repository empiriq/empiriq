<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\MarketStream;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\RateLimit;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\MarketStream\Results\UserDataStreamStartResult;

readonly class UserDataStreamStartResponse
{
    /**
     * @param string $id
     * @param int $status
     * @param UserDataStreamStartResult $result
     * @param RateLimit[] $rateLimits
     */
    public function __construct(
        public string $id,
        public int $status,
        public UserDataStreamStartResult $result,
        public array $rateLimits = [],
    ) {
    }
}
