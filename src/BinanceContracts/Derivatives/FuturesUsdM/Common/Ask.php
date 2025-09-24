<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common;

readonly class Ask
{
    public function __construct(
        public float $price,
        public float $quantity,
    ) {
    }
}
