<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Responses\MarketData\Results;

use Empiriq\BinanceContracts\Markets\Spot\Common\Ask;
use Empiriq\BinanceContracts\Markets\Spot\Common\Bid;

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
