<?php

namespace Empiriq\Contracts\Messaging;

/**
 * Messenger envelope for domain events.
 *
 * @api
 */
final readonly class DomainEventMessage
{
    public function __construct(
        public object $event,
        public ?string $eventName = null,
    ) {
    }
}
