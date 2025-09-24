<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Events\User;

use Empiriq\BinanceContracts\Spot\Spot\Common\EventInterface;

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
