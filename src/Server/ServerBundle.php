<?php

namespace Empiriq\Server;

use Empiriq\Server\DependencyInjection\ServerExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ServerBundle extends Bundle //todo no extend, implement interface
{
    /**
     * Returns the bundle's container extension class.
     */
    protected function getContainerExtensionClass(): string
    {
        return ServerExtension::class;
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new ServerExtension();
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     */
    public function build(ContainerBuilder $container): void
    {
    }
}
