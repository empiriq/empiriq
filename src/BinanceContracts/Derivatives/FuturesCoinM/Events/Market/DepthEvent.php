<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Events\Market;

use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\Ask;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\Bid;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\EventInterface;

readonly class DepthEvent implements EventInterface
{
    /**
     * @param int $time
     * @param string $symbol
     * @param int $firstId
     * @param int $finalId
     * @param Bid[] $bids
     * @param Ask[] $asks
     */
    public function __construct(
        public int $time,
        public string $symbol,
        public int $firstId,
        public int $finalId,
        public array $bids,
        public array $asks,
    ) {
    }
}
