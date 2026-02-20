<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUsdM\Events\User\Payload;

use Empiriq\BinanceContracts\Markets\FuturesUsdM\Common\AccountUpdateReason;

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
