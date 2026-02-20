<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Responses\MarketStream;

use Empiriq\BinanceContracts\Markets\FuturesUsdM\Common\RateLimit;
use Empiriq\BinanceContracts\Markets\FuturesUsdM\Responses\MarketStream\Results\UserDataStreamStartResult;

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
