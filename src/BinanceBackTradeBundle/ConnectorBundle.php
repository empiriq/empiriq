<?php

namespace Empiriq\BinanceBackTradeBundle;

use Empiriq\BinanceBackTradeBundle\DependencyInjection\BinanceHistoryConnectorExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConnectorBundle extends Bundle //todo no extend, implement interface
{
    /**
     * Returns the bundle's container extension class.
     */
    protected function getContainerExtensionClass(): string
    {
        return BinanceHistoryConnectorExtension::class;
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new BinanceHistoryConnectorExtension();
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
     */
    public function boot(): void
    {
        /** @var Connector $connector */
        $connector = $this->container->get(Connector::class);
        $connector->run();
    }
}
