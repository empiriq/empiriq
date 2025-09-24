<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Responses\Authentication;

use Empiriq\BinanceContracts\Spot\Spot\Common\RateLimit;
use Empiriq\BinanceContracts\Spot\Spot\Responses\Authentication\Results\SessionStatusResult;

readonly class SessionStatusResponse
{
    /**
     * @param string $id
     * @param int $status
     * @param SessionStatusResult $result
     * @param RateLimit[] $rateLimits
     */
    public function __construct(
        public string $id,
        public int $status,
        public SessionStatusResult $result,
        public array $rateLimits = [],
    ) {
    }
}
