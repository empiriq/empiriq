<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\General\Results;

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
