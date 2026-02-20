<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Events\Market;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\EventInterface;

/**
 * @link https://developers.binance.com/docs/binance-spot-api-docs/web-socket-streams#trade-streams
 * @link https://developers.binance.com/docs/derivatives/usds-margined-futures/websocket-market-streams/Aggregate-Trade-Streams
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
