<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\User;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\EventInterface;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\Order;

/**
 * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/user-data-streams/Event-Order-Update
 */
readonly class OrderTradeUpdateEvent implements EventInterface
{
    /**
     * @param int $time
     * @param int $transactionTime
     * @param Order $order
     */
    public function __construct(
        public int $time,
        public int $transactionTime,
        public Order $order,
    ) {
    }
}
