<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCoinM\Responses\MarketData\Results;

use Empiriq\BinanceContracts\Markets\FuturesCoinM\Common\Ask;
use Empiriq\BinanceContracts\Markets\FuturesCoinM\Common\Bid;

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
