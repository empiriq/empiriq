<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\Authentication;

use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\RateLimit;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\Authentication\Results\SessionStatusResult;

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
