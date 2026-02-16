<?php

namespace Empiriq\SymfonyEventCollector;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Extracts declared (potential) events from container definitions.
 *
 * Implementations must operate at design-time and must not rely on
 * service tags or compiler passes.
 */
interface HandlerInterface
{
    /**
     * @return string[] Event names.
     */
    public function collect(ContainerBuilder $container): array;
}
