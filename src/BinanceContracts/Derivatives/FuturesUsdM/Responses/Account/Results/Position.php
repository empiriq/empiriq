<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Account\Results;

readonly class Position
{
    /**
     * @param string $symbol Trading pair symbol (e.g., BTCUSDT)
     * @param string $positionSide Position side (BOTH, LONG, SHORT)
     * @param string $positionAmt Position amount
     * @param string $unrealizedProfit Unrealized profit
     * @param string $isolatedMargin Isolated margin
     * @param string $notional Notional value
     * @param string $isolatedWallet Isolated wallet balance
     * @param string $initialMargin Initial margin required
     * @param string $maintMargin Maintenance margin required
     * @param int $updateTime Last update timestamp (ms)
     */
    public function __construct(
        public string $symbol,
        public string $positionSide,
        public string $positionAmt,
        public string $unrealizedProfit,
        public string $isolatedMargin,
        public string $notional,
        public string $isolatedWallet,
        public string $initialMargin,
        public string $maintMargin,
        public int $updateTime,
    ) {
    }
}
