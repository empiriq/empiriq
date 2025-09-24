<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Account;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\RateLimit;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Account\Results\Balance;

readonly class AccountBalanceResponse
{
    /**
     * @param string $id
     * @param int $status
     * @param Balance[] $result
     * @param RateLimit[] $rateLimits
     */
    public function __construct(
        public string $id,
        public int $status,
        public array $result,
        public array $rateLimits = [],
    ) {
    }
}
