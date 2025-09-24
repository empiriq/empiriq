<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Responses\General;

use Empiriq\BinanceContracts\Spot\Spot\Common\RateLimit;
use Empiriq\BinanceContracts\Spot\Spot\Responses\General\Payloads\TimeResult;

readonly class TimeResponse
{
    /**
     * @param string $id
     * @param int $status
     * @param TimeResult $result
     * @param RateLimit[] $rateLimits
     */
    public function __construct(
        public string $id,
        public int $status,
        public TimeResult $result,
        public array $rateLimits = [],
    ) {
    }
}
