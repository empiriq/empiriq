<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Responses\Authentication;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\RateLimit;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\Authentication\Results\AccountStatusResult;

readonly class AccountStatusResponse
{
    /**
     * @param string $id
     * @param int $status
     * @param AccountStatusResult $result
     * @param RateLimit[] $rateLimits
     */
    public function __construct(
        public string $id,
        public int $status,
        public AccountStatusResult $result,
        public array $rateLimits = [],
    ) {
    }
}
