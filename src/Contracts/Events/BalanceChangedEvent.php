<?php

namespace Empiriq\Contracts\Events;

use Empiriq\Contracts\Entities\BalanceEntity;

final readonly class BalanceChangedEvent
{
    /**
     * @param BalanceEntity $entity
     * @param array<string, array{old:mixed,new:mixed}> $changes
     */
    public function __construct(
        public BalanceEntity $entity,
        public array $changes,
    ) {
    }
}
