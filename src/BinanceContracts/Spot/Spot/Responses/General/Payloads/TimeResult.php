<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Responses\General\Payloads;

readonly class TimeResult
{
    /**
     * @param int $serverTime
     */
    public function __construct(
        public int $serverTime,
    ) {
    }
}
