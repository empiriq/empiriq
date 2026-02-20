<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Responses\Account;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\RateLimit;
use Empiriq\BinanceContracts\Markets\FuturesUm\Responses\Account\Results\AccountInformationResult;

readonly class AccountInformationResponse
{
    /**
     * @param string $id Request ID
     * @param int $status Response status code
     * @param AccountInformationResult $result Account details
     * @param RateLimit[] $rateLimits Rate limits applied
     */
    public function __construct(
        public string $id,
        public int $status,
        public AccountInformationResult $result,
        public array $rateLimits = [],
    ) {
    }
}
