<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Common;

readonly class Ask
{
    public function __construct(
        public float $price,
        public float $quantity,
    ) {
    }
}
