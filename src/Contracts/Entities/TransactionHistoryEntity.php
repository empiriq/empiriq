<?php

namespace Empiriq\Contracts\Entities;

use Empiriq\Contracts\Common\TransactionType;

final class TransactionHistoryEntity
{
    public function __construct(
        public int $time,
        public TransactionType $type,
        public float $amount,
        public string $asset,
        public string $symbol,
    ) {
    }
}
