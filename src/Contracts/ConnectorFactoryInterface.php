<?php

namespace Empiriq\Contracts;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;

interface ConnectorFactoryInterface
{
    /**
     * @param array<string, string[]> $markets
     * @param array $streams
     * @param array<string, mixed> $options
     * @param EventDispatcherInterface $dispatcher
     * @param SerializerInterface $serializer
     * @return ConnectorInterface
     */
    public function createConnector(
        array $markets,
        array $streams,
        array $options,
        EventDispatcherInterface $dispatcher,
        SerializerInterface $serializer,
    ): ConnectorInterface;
}
