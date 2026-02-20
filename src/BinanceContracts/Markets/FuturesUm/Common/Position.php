<?php

namespace Empiriq\BinanceContracts\Markets\FuturesUm\Common;

readonly class Position
{
    public function __construct(
        public string $symbol,
        public PositionSide $side,
        public float $positionAmount,
        public MarginType $type,
        public ?float $isolatedWallet,
        public float $markPrice,
        public float $unrealizedPnl,
        public float $maintenanceMargin,
    ) {
    }
}
