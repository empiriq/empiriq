<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common;

readonly class Balance
{
    private function __construct(
        public string $asset,
        public float $free,
        public float $locked,
    ) {
    }
}
