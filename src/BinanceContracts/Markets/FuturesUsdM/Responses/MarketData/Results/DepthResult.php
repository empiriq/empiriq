<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Responses\MarketData\Results;

use Empiriq\BinanceContracts\Markets\FuturesUsdM\Common\Ask;
use Empiriq\BinanceContracts\Markets\FuturesUsdM\Common\Bid;

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
