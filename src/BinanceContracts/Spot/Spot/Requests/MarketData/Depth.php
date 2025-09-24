<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Requests\MarketData;

readonly class Depth
{
    public function __construct(
        public string $symbol,
        public int $limit,
    ) {
    }
}
