<?php

namespace Empiriq\Contracts\Entities;

/**
 * @api
 */
final class TradeEntity
{
    public function __construct(
        public int $time,
        public string $symbol,
        public float $price,
        public float $quantity,
        public bool $isBuyerMaker
    ) {
    }
}
