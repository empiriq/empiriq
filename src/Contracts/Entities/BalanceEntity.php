<?php

namespace Empiriq\Contracts\Entities;

final class BalanceEntity
{
    public function __construct(
        public string $asset,
        public float $balance,
    ) {
    }
}
