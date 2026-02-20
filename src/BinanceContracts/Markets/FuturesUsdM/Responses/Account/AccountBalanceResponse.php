<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Responses\Account;

use Empiriq\BinanceContracts\Markets\FuturesUsdM\Common\RateLimit;
use Empiriq\BinanceContracts\Markets\FuturesUsdM\Responses\Account\Results\Balance;

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
