<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Account\Results;

readonly class Asset
{
    /**
     * @param string $asset Asset name (e.g., USDT, USDC, BTC)
     * @param string $walletBalance Wallet balance
     * @param string $unrealizedProfit Unrealized profit
     * @param string $marginBalance Margin balance
     * @param string $maintMargin Maintenance margin required
     * @param string $initialMargin Total initial margin required
     * @param string $positionInitialMargin Initial margin required for positions
     * @param string $openOrderInitialMargin Initial margin required for open orders
     * @param string $crossWalletBalance Crossed wallet balance
     * @param string $crossUnPnl Unrealized profit of crossed positions
     * @param string $availableBalance Available balance
     * @param string $maxWithdrawAmount Maximum withdrawal amount
     * @param int $updateTime Last update timestamp (ms)
     */
    public function __construct(
        public string $asset,
        public string $walletBalance,
        public string $unrealizedProfit,
        public string $marginBalance,
        public string $maintMargin,
        public string $initialMargin,
        public string $positionInitialMargin,
        public string $openOrderInitialMargin,
        public string $crossWalletBalance,
        public string $crossUnPnl,
        public string $availableBalance,
        public string $maxWithdrawAmount,
        public int $updateTime,
    ) {
    }
}
