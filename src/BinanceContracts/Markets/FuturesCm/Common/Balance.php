<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCm\Common;

readonly class Balance
{
    private function __construct(
        public string $asset,
        public float $free,
        public float $locked,
    ) {
    }
}
