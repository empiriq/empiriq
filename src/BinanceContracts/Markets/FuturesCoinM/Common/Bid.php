<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCoinM\Common;

readonly class Bid
{
    public function __construct(
        public float $price,
        public float $quantity,
    ) {
    }
}
