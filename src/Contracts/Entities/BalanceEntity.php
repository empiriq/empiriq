<?php

namespace Empiriq\Contracts\Entities;

class BalanceEntity
{
    public function __construct(
        public string $asset,
        public float $balance,
    ) {
    }
}
