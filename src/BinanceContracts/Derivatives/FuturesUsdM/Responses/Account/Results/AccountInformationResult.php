<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Account\Results;

readonly class AccountInformationResult
{
    /**
     * @param string $totalInitialMargin Total initial margin required with current mark price
     * @param string $totalMaintMargin Total maintenance margin required
     * @param string $totalWalletBalance Total wallet balance
     * @param string $totalUnrealizedProfit Total unrealized profit
     * @param string $totalMarginBalance Total margin balance
     * @param string $totalPositionInitialMargin Initial margin required for positions with current mark price
     * @param string $totalOpenOrderInitialMargin Initial margin required for open orders with current mark price
     * @param string $totalCrossWalletBalance Crossed wallet balance
     * @param string $totalCrossUnPnl Unrealized profit of crossed positions
     * @param string $availableBalance Available balance
     * @param string $maxWithdrawAmount Maximum amount available for withdrawal
     * @param Asset[] $assets List of asset balances
     * @param Position[] $positions List of open positions
     */
    public function __construct(
        public string $totalInitialMargin,
        public string $totalMaintMargin,
        public string $totalWalletBalance,
        public string $totalUnrealizedProfit,
        public string $totalMarginBalance,
        public string $totalPositionInitialMargin,
        public string $totalOpenOrderInitialMargin,
        public string $totalCrossWalletBalance,
        public string $totalCrossUnPnl,
        public string $availableBalance,
        public string $maxWithdrawAmount,
        public array $assets,
        public array $positions,
    ) {
    }
}
