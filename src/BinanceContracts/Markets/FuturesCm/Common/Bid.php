<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCm\Common;

readonly class Bid
{
    public function __construct(
        public float $price,
        public float $quantity,
    ) {
    }
}
