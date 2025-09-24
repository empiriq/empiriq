<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Common;

readonly class Bid
{
    public function __construct(
        public float $price,
        public float $quantity,
    ) {
    }
}
