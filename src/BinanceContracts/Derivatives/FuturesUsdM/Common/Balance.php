<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common;

//todo move to local?
readonly class Balance
{
    private function __construct(
        public string $asset,
        public float $free,
        public float $locked,
    ) {
    }
}
