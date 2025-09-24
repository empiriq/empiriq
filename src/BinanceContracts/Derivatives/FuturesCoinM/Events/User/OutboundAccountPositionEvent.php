<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Events\User;

use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\Balance;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\EventInterface;

/**
 * outboundAccountPosition is sent any time an account balance has changed and contains the assets that were possibly
 * changed by the event that generated the balance change.
 * @link https://developers.binance.com/docs/binance-spot-api-docs/user-data-stream#account-update
 */
readonly class OutboundAccountPositionEvent implements EventInterface
{
    /**
     * @param int $time
     * @param int $lastAccountUpdate
     * @param Balance[] $balances
     */
    public function __construct(
        public int $time,
        public int $lastAccountUpdate,
        public array $balances,
    ) {
    }
}
