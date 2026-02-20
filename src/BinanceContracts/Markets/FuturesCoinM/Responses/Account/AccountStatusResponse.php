<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCoinM\Responses\Account;

use Empiriq\BinanceContracts\Markets\FuturesCoinM\Common\RateLimit;
use Empiriq\BinanceContracts\Markets\FuturesCoinM\Responses\Account\Results\AccountStatusResult;

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
