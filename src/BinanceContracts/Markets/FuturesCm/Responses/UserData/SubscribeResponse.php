<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCm\Responses\UserData;

use Empiriq\BinanceContracts\Markets\FuturesCm\Common\RateLimit;
use Empiriq\BinanceContracts\Markets\FuturesCm\Responses\UserData\Results\ListenKey;

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
