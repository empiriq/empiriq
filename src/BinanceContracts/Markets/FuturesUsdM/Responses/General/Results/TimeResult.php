<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Responses\General\Results;

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
