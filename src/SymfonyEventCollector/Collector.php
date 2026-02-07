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
     * @param iterable<HandlerInterface> $extractors
     */
    public function __construct(
        private iterable $extractors
    ) {
    }

    /**
     * Collects and merges declared events from all extractors.
     *
     * @return array<string, string[]> Map of event name to declaring classes
     */
    public function collect(ContainerBuilder $container): array
    {
        $events = [];
        foreach ($this->extractors as $extractor) {
            foreach ($extractor->collect($container) as $event => $classes) {
                $events[$event] ??= [];
                $events[$event] = array_merge($events[$event], $classes);
            }
        }
        foreach ($events as &$classes) {
            $classes = array_values(array_unique($classes));
        }

        return $events;
    }
}
