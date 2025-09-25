<?php

namespace Empiriq\Contracts\Entities;

use Empiriq\Contracts\Common\OrderSide;
use Empiriq\Contracts\Common\OrderStatus;
use Empiriq\Contracts\Common\OrderType;

final class OrderHistoryEntity
{
    public function __construct(
        public int $time,
        public string $symbol,
        public OrderType $type,
        public OrderSide $side,
        public float $average,
        public float $price,
        public float $executed,
        public float $amount,
        public bool $reduceOnly,
        public bool $postOnly,
        public mixed $triggerConditions,
        public OrderStatus $status,
    ) {
    }
}
