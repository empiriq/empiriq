<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Responses\MarketStream;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\RateLimit;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\MarketStream\Results\UserDataStreamStartResult;

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
