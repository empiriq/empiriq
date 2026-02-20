<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Responses\UserData;

use Empiriq\BinanceContracts\Markets\Spot\Common\RateLimit;
use Empiriq\BinanceContracts\Markets\Spot\Responses\UserData\Results\ListenKey;

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
