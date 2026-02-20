<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Events\Market;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\Ask;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\Bid;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\EventInterface;

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
