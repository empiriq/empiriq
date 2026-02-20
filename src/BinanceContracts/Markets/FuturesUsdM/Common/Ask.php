<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Common;

readonly class Ask
{
    public function __construct(
        public float $price,
        public float $quantity,
    ) {
    }
}
