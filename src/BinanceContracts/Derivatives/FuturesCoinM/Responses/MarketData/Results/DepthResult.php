<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Responses\MarketData\Results;

use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\Ask;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\Bid;

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
