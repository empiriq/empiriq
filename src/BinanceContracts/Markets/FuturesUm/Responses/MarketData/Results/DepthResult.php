<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Responses\MarketData\Results;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\Ask;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\Bid;

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
