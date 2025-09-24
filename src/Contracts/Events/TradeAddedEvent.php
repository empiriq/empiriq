<?php

namespace Empiriq\Contracts\Events;

use Empiriq\Contracts\Entities\TradeEntity;

final readonly class TradeAddedEvent
{
    /**
     * @param TradeEntity $entity
     */
    public function __construct(
        public TradeEntity $entity,
    ) {
    }
}
