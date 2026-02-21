<?php

namespace Empiriq\Contracts\Messaging;

use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Messenger-based event publisher.
 *
 * @api
 */
final readonly class SymfonyMessengerEventPublisher implements EventPublisherInterface
{
    public function __construct(
        private MessageBusInterface $bus,
    ) {
    }

    #[\Override]
    public function publish(object $event, ?string $eventName = null): void
    {
        $this->bus->dispatch(new DomainEventMessage($event, $eventName));
    }
}
