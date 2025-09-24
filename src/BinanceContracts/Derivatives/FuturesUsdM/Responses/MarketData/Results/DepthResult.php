<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\MarketData\Results;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\Ask;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\Bid;

class DepthResult
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
