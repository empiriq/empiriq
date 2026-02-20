<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Events\User;

use Empiriq\BinanceContracts\Markets\FuturesUsdM\Common\EventInterface;
use Empiriq\BinanceContracts\Markets\FuturesUsdM\Events\User\Payload\AccountUpdateData;

/**
 * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/user-data-streams/Event-Balance-and-Position-Update
 */
readonly class AccountUpdateEvent implements EventInterface
{
    /**
     * @param int $time
     * @param int $transactionTime
     * @param AccountUpdateData $updateData
     */
    public function __construct(
        public int $time,
        public int $transactionTime,
        public AccountUpdateData $updateData,
    ) {
    }
}
