<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Events\Market;

use Empiriq\BinanceContracts\Spot\Spot\Common\EventInterface;

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
