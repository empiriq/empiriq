<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Events\Market;

use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\EventInterface;

/**
 * @link https://developers.binance.com/docs/binance-spot-api-docs/web-socket-streams#trade-streams
 */
readonly class TradeEvent implements EventInterface
{
    public function __construct(
        public int $time,
        public string $symbol,
        public float $price,
        public float $quantity,
        public bool $isBuyerMaker
    ) {
    }
}
