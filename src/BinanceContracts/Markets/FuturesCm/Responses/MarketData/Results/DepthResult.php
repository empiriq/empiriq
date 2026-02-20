<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCm\Responses\MarketData\Results;

use Empiriq\BinanceContracts\Markets\FuturesCm\Common\Ask;
use Empiriq\BinanceContracts\Markets\FuturesCm\Common\Bid;

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
