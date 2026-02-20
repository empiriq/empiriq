<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCm\Events\Market;

use Empiriq\BinanceContracts\Markets\FuturesCm\Common\EventInterface;

/**
 * @link https://developers.binance.com/docs/binance-spot-api-docs/web-socket-streams#trade-streams
 * @link https://developers.binance.com/docs/derivatives/coin-margined-futures/websocket-market-streams/Aggregate-Trade-Streams
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
