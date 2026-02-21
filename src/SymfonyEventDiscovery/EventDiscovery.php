<?php

namespace Empiriq\SymfonyEventDiscovery;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Aggregates multiple event extractors into a single declared event catalog.
 *
 * The resulting catalog represents events that the codebase declares
 * it can listen to, not events that are guaranteed to have runtime listeners.
 */
final readonly class EventDiscovery
{
    /**
     * @param iterable<ExtractorInterface> $extractors
     */
    public function __construct(
        private iterable $extractors
    ) {
    }

    /**
     * Collects declared event names from all extractors.
     *
     * @return string[] Event names.
     */
    public function discover(ContainerBuilder $container): array
    {
        $events = [];
        foreach ($this->extractors as $extractor) {
            foreach ($extractor->collect($container) as $event) {
                $events[] = $event;
            }
        }

        $events = array_values(array_unique($events));
        sort($events);

        return $events;
    }
}
