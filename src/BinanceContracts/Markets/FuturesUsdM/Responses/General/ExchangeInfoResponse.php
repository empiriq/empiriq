<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Responses\General;

use Empiriq\BinanceContracts\Markets\FuturesUsdM\Common\RateLimit;
use Empiriq\BinanceContracts\Markets\FuturesUsdM\Responses\General\Results\ExchangeInfoResult;

readonly class ExchangeInfoResponse
{
    /**
     * @param string $id
     * @param int $status
     * @param ExchangeInfoResult $result
     * @param RateLimit[] $rateLimits
     */
    public function __construct(
        public string $id,
        public int $status,
        public ExchangeInfoResult $result,
        public array $rateLimits = [],
    ) {
    }
}
