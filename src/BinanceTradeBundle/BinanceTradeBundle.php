<?php

namespace Empiriq\BinanceTradeBundle;

use Empiriq\BinanceTradeBundle\DependencyInjection\BinanceApiConnectorExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @api Provides interaction with the Binance exchange
 */
final class BinanceTradeBundle extends Bundle
{
    /**
     * Returns the bundle's container extension class.
     */
    #[\Override]
    protected function getContainerExtensionClass(): string
    {
        return BinanceApiConnectorExtension::class;
    }

    #[\Override]
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new BinanceApiConnectorExtension();
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     */
    #[\Override]
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }

    /**
     * @return void
     * @throws \Throwable
     */
    #[\Override]
    public function boot(): void
    {
    }
}
