<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Events\User\Payload;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\AccountUpdateReason;

readonly class AccountUpdateData
{
    /**
     * @param AccountUpdateReason $reason
     * @param BalanceUpdate[] $balances
     * @param PositionUpdate[] $positions
     */
    public function __construct(
        public AccountUpdateReason $reason,
        public array $balances,
        public array $positions,
    ) {
    }
}
