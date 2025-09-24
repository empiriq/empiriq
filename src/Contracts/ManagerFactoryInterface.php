<?php

namespace Empiriq\Contracts;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface ManagerFactoryInterface
{
    /**
     * @param array<string, string[]> $markets
     * @param array<string, mixed> $options
     * @param ConnectorFactoryInterface $environmentFactory
     * @param EventDispatcherInterface $dispatcher
     * @return ManagerInterface
     */
    public function createManager(
        array $markets,
        array $options,
        ConnectorFactoryInterface $environmentFactory,
        EventDispatcherInterface $dispatcher,
    ): ManagerInterface;
}
