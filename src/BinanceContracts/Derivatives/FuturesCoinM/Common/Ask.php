<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common;

readonly class Ask
{
    public function __construct(
        public float $price,
        public float $quantity,
    ) {
    }
}
