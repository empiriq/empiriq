<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCoinM\Events\Market;

use Empiriq\BinanceContracts\Markets\FuturesCoinM\Common\Ask;
use Empiriq\BinanceContracts\Markets\FuturesCoinM\Common\Bid;
use Empiriq\BinanceContracts\Markets\FuturesCoinM\Common\EventInterface;

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
