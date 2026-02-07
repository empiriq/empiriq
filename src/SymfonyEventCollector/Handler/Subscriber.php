<?php

namespace Empiriq\SymfonyEventCollector\Handler;

use Empiriq\SymfonyEventCollector\HandlerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Extracts events declared by EventSubscriberInterface implementations.
 *
 * Events are obtained from static getSubscribedEvents() declarations
 * without assuming runtime service registration.
 */
final class Subscriber implements HandlerInterface
{
    public function collect(ContainerBuilder $container): array
    {
        $events = [];
        foreach ($container->getDefinitions() as $definition) {
            $class = $definition->getClass();
            if (!$class || !class_exists($class)) {
                continue;
            }
            if (!is_subclass_of($class, EventSubscriberInterface::class)) {
                continue;
            }
            foreach (array_keys($class::getSubscribedEvents()) as $eventName) {
                $events[$eventName][] = $class;
            }
        }

        return $events;
    }
}
