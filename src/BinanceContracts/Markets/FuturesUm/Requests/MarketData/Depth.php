<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Requests\MarketData;

readonly class Depth
{
    public function __construct(
        public string $symbol,
        public int $limit,
    ) {
    }
}
