<?php

namespace Empiriq\Contracts\Messaging;

/**
 * Publishes domain events via an application transport.
 *
 * @api
 */
interface EventPublisherInterface
{
    public function publish(object $event, ?string $eventName = null): void;
}
