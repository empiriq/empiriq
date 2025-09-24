<?php

namespace Empiriq\BinanceTradeBundle;

use Empiriq\BinanceTradeBundle\DependencyInjection\BinanceApiConnectorExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ConnectorBundle extends Bundle //todo no extend, implement interface
{
    /**
     * Returns the bundle's container extension class.
     */
    protected function getContainerExtensionClass(): string
    {
        return BinanceApiConnectorExtension::class;
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new BinanceApiConnectorExtension();
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function boot(): void
    {
    }
}
