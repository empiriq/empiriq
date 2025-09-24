<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Account\Results;

readonly class Balance
{
    /**
     * @param string $accountAlias unique account code
     * @param string $asset asset name
     * @param float $balance wallet balance
     * @param float $crossWalletBalance crossed wallet balance
     * @param float $crossUnPnl unrealized profit of crossed positions
     * @param float $availableBalance available balance
     * @param float $maxWithdrawAmount maximum amount for transfer out
     * @param bool $marginAvailable whether the asset can be used as margin in Multi-Assets mode
     * @param string $updateTime
     */
    public function __construct(
        public string $accountAlias,
        public string $asset,
        public float $balance,
        public float $crossWalletBalance,
        public float $crossUnPnl,
        public float $availableBalance,
        public float $maxWithdrawAmount,
        public bool $marginAvailable,
        public string $updateTime,
    ) {
    }
}
