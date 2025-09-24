<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Requests\MarketData;

readonly class Depth
{
    public function __construct(
        public string $symbol,
        public int $limit,
    ) {
    }
}
