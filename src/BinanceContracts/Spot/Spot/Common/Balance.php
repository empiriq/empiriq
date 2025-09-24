<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Common;

readonly class Balance
{
    private function __construct(
        public string $asset,
        public float $free,
        public float $locked,
    ) {
    }
}
