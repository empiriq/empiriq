<?php

namespace Empiriq\Contracts\Events;

use Empiriq\Contracts\Entities\BalanceEntity;

final readonly class BalanceAddedEvent
{
    /**
     * @param BalanceEntity $entity
     */
    public function __construct(
        public BalanceEntity $entity,
    ) {
    }
}
