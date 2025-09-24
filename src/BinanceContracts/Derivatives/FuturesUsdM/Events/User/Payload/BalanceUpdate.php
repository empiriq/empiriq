<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\User\Payload;

readonly class BalanceUpdate
{
    /**
     * @param string $asset
     * @param float $walletBalance
     * @param float $crossWalletBalance
     * @param float $balanceChange
     */
    public function __construct(
        public string $asset,
        public float $walletBalance,
        public float $crossWalletBalance,
        public float $balanceChange,
    ) {
    }
}
