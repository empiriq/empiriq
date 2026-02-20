<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Events\User;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\EventInterface;
use Empiriq\BinanceContracts\Markets\FuturesUm\Common\Position;

/**
 * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/user-data-streams/Event-Margin-Call
 */
readonly class MarginCallEvent implements EventInterface
{
    /**
     * @param int $time
     * @param float|null $crossWalletBalance
     * @param Position $positions
     */
    public function __construct(
        public int $time,
        public ?float $crossWalletBalance,
        public Position $positions,
    ) {
    }
}
