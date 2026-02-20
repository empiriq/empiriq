<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Common;

readonly class Balance
{
    private function __construct(
        public string $asset,
        public float $free,
        public float $locked,
    ) {
    }
}
