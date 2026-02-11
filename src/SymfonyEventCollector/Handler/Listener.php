<?php

namespace Empiriq\SymfonyEventCollector\Handler;

use Empiriq\SymfonyEventCollector\HandlerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * Extracts events declared via AsEventListener attributes.
 *
 * This extractor inspects class-level attributes and does not rely
 * on Symfony autoconfiguration or service tags.
 */
final class Listener implements HandlerInterface
{
    public function collect(ContainerBuilder $container): array
    {
        $events = [];

        foreach ($container->getDefinitions() as $definition) {
            $class = $definition->getClass();

            if (!$class || !class_exists($class)) {
                continue;
            }

            $reflection = new \ReflectionClass($class);

            foreach ($reflection->getAttributes(AsEventListener::class) as $attribute) {
                $args = $attribute->getArguments();

                if (!isset($args['event'])) {
                    continue;
                }

                $events[$args['event']][] = $class;
            }
        }

        return $events;
    }
}
