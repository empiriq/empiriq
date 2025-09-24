<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Responses\Trading\Results;

readonly class OrderNewFill
{
    public function __construct(
        public string $matchType,
        public float $price,
        public float $qty,
        public float $commission,
        public string $commissionAsset,
        public int $tradeId,
        public int $allocId,
    ) {
    }
}
