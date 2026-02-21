<?php

namespace Empiriq\SymfonyEventDiscovery\Extractor;

use Empiriq\SymfonyEventDiscovery\ExtractorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Extracts events declared by EventSubscriberInterface implementations.
 *
 * Events are obtained from static getSubscribedEvents() declarations
 * without assuming runtime service registration.
 */
final class SubscriberExtractor implements ExtractorInterface
{
    #[\Override]
    public function collect(ContainerBuilder $container): array
    {
        $events = [];
        foreach ($container->getDefinitions() as $definition) {
            $class = $definition->getClass();
            if (!is_string($class) || $class === '' || !class_exists($class)) {
                continue;
            }
            if (!is_subclass_of($class, EventSubscriberInterface::class)) {
                continue;
            }
            foreach (array_keys($class::getSubscribedEvents()) as $eventName) {
                $events[] = $eventName;
            }
        }

        return $events;
    }
}
