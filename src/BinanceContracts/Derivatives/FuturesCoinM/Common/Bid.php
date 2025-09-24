<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common;

readonly class Bid
{
    public function __construct(
        public float $price,
        public float $quantity,
    ) {
    }
}
