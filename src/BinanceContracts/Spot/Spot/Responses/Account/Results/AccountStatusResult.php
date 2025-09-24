<?php

namespace Empiriq\BinanceContracts\Spot\Spot\Responses\Account\Results;

use Empiriq\BinanceContracts\Spot\Spot\Common\Balance;

readonly class AccountStatusResult
{
    /**
     * @param float $makerCommission
     * @param float $takerCommission
     * @param float $buyerCommission
     * @param float $sellerCommission
     * @param bool $canTrade
     * @param bool $canWithdraw
     * @param bool $canDeposit
     * @param CommissionRates $commissionRates
     * @param bool $brokered
     * @param bool $requireSelfTradePrevention
     * @param bool $preventSor
     * @param int $updateTime
     * @param AccountType $accountType
     * @param Balance[] $balances
     * @param string[] $permissions
     * @param int $uid
     */
    public function __construct(
        public float $makerCommission,
        public float $takerCommission,
        public float $buyerCommission,
        public float $sellerCommission,
        public bool $canTrade,
        public bool $canWithdraw,
        public bool $canDeposit,
        public CommissionRates $commissionRates,
        public bool $brokered,
        public bool $requireSelfTradePrevention,
        public bool $preventSor,
        public int $updateTime,
        public AccountType $accountType,
        public array $balances,
        public array $permissions,
        public int $uid,
    ) {
    }
}
