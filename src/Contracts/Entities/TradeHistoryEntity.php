<?php

namespace Empiriq\Contracts\Entities;

use Empiriq\Contracts\Common\OrderSide;

final class TradeHistoryEntity
{
    public function __construct(
        public int $time,
        public string $symbol,
        public OrderSide $side,
        public float $price,
        public float $quantity,
        public float $fee,
        public mixed $role,
        public float $realizedPnl,
    ) {
    }
}
