<?php

namespace Empiriq\SymfonyEventCollector;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Aggregates multiple event extractors into a single declared event catalog.
 *
 * The resulting catalog represents events that the codebase declares
 * it can listen to, not events that are guaranteed to have runtime listeners.
 */
readonly class Collector
{
    /**
     * @param iterable<HandlerInterface> $handlers
     */
    public function __construct(
        private iterable $handlers
    ) {
    }

    /**
     * Collects declared event names from all handlers.
     *
     * @return string[] Event names.
     */
    public function collect(ContainerBuilder $container): array
    {
        $events = [];
        foreach ($this->handlers as $handler) {
            foreach ($handler->collect($container) as $event) {
                $events[] = $event;
            }
        }

        $events = array_values(array_unique($events));
        sort($events);

        return $events;
    }
}
