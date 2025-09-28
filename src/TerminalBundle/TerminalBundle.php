<?php

namespace Empiriq\TerminalBundle;

use Empiriq\TerminalBundle\DependencyInjection\ServerExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class TerminalBundle extends Bundle
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
