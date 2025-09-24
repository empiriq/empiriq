<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Authentication;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\RateLimit;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Authentication\Results\AccountStatusResult;

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
