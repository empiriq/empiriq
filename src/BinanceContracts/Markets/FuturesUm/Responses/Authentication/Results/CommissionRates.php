<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Responses\Authentication\Results;

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
