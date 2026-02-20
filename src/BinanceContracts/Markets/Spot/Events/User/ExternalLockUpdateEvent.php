<?php

namespace Empiriq\BinanceContracts\Markets\Spot\Events\User;

use Empiriq\BinanceContracts\Markets\Spot\Common\EventInterface;

/**
 * https://developers.binance.com/docs/binance-spot-api-docs/user-data-stream#external-lock-update
 */
readonly class ExternalLockUpdateEvent implements EventInterface
{
    public function __construct(
        public int $time,
        public string $asset,
        public float $delta,
        public int $transactionTime,
    ) {
    }
}
