<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Events\User\Payload;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\PositionSide;

readonly class PositionUpdate
{
    /**
     * @param string $symbol
     * @param float $positionAmt
     * @param float $entryPrice
     * @param float $breakEvenPrice
     * @param float $accumulatedRealized
     * @param float $unrealizedPnl
     * @param MarginType $marginType
     * @param float $isolatedWallet
     * @param PositionSide $positionSide
     */
    public function __construct(
        public string $symbol,
        public float $positionAmt,
        public float $entryPrice,
        public float $breakEvenPrice,
        public float $accumulatedRealized,
        public float $unrealizedPnl,
        public MarginType $marginType,
        public float $isolatedWallet,
        public PositionSide $positionSide,
    ) {
    }
}
