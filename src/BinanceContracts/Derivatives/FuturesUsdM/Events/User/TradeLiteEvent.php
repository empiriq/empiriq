<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\User;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\EventInterface;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\OrderSide;

/**
 * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/user-data-streams/Event-Trade-Lite
 */
readonly class TradeLiteEvent implements EventInterface
{
    /**
     * @param int $time
     * @param int $transactionTime
     * @param string $symbol
     * @param float $originalQuantity
     * @param float $originalPrice
     * @param bool $isMaker
     * @param string $clientOrderId
     * @param OrderSide $side
     * @param float $lastFilledPrice
     * @param float $lastFilledQuantity
     * @param int $tradeId
     * @param int $orderId
     */
    public function __construct(
        public int $time,
        public int $transactionTime,
        public string $symbol,
        public float $originalQuantity,
        public float $originalPrice,
        public bool $isMaker,
        public string $clientOrderId,
        public OrderSide $side,
        public float $lastFilledPrice,
        public float $lastFilledQuantity,
        public int $tradeId,
        public int $orderId,
    ) {
    }
}
