<?php

namespace Empiriq\BinanceContracts\Markets\FuturesCoinM\Events\User;

use Empiriq\BinanceContracts\Markets\FuturesCoinM\Common\EventInterface;

/**
 * @link https://developers.binance.com/docs/binance-spot-api-docs/user-data-stream#balance-update
 */
readonly class BalanceUpdateEvent implements EventInterface
{
    public function __construct(
        public int $time,
        public string $asset,
        public float $delta,
        public int $clearTime,
    ) {
    }
}
