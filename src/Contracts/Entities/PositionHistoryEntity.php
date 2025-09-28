<?php

namespace Empiriq\Contracts\Entities;

use Empiriq\Contracts\Common\PositionSide;

/**
 * @api
 */
final class PositionHistoryEntity
{
    public function __construct(
        public string $symbol,
        public PositionSide $side,
        public float $closingPnl,
        public float $entryPrice,
        public float $avgClosePrice,
        public float $maxOpenInterest,
        public float $closedVol,
        public float $opened,
        public float $closed,
    ) {
    }
}
