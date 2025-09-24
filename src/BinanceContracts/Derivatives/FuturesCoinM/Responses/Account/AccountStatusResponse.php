<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\Account;

use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\RateLimit;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\Account\Results\AccountStatusResult;

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
