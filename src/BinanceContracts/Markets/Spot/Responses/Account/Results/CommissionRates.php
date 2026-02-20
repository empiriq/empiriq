<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Responses\Account\Results;

readonly class CommissionRates
{
    public function __construct(
        public float $maker,
        public float $taker,
        public float $buyer,
        public float $seller,
    ) {
    }
}
