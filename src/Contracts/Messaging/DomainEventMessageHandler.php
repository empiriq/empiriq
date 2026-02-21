<?php

namespace Empiriq\Contracts\Messaging;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Forwards messenger messages back into Symfony's event dispatcher.
 *
 * @api
 */
final readonly class DomainEventMessageHandler
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    public function __invoke(DomainEventMessage $message): void
    {
        $this->dispatcher->dispatch($message->event, $message->eventName);
    }
}
