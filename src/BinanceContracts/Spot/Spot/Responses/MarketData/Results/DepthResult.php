<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Responses\MarketData\Results;

use Empiriq\BinanceContracts\Spot\Spot\Common\Ask;
use Empiriq\BinanceContracts\Spot\Spot\Common\Bid;

readonly class DepthResult
{
    /**
     * @param int $lastUpdateId
     * @param Bid[] $bids
     * @param Ask[] $asks
     */
    public function __construct(
        public int $lastUpdateId,
        public array $bids,
        public array $asks,
    ) {
    }
}
