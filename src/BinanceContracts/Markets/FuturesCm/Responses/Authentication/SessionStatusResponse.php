<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCm\Responses\Authentication;

use Empiriq\BinanceContracts\Markets\FuturesCm\Common\RateLimit;
use Empiriq\BinanceContracts\Markets\FuturesCm\Responses\Authentication\Results\SessionStatusResult;

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
