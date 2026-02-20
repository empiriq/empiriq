<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Common;

readonly class Ask
{
    public function __construct(
        public float $price,
        public float $quantity,
    ) {
    }
}
