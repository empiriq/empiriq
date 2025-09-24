<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\User;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\EventInterface;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\Position;

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
