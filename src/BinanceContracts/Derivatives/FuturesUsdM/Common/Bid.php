<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common;

readonly class Bid
{
    public function __construct(
        public float $price,
        public float $quantity,
    ) {
    }
}
