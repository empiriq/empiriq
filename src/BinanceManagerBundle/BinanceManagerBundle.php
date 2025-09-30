<?php

namespace Empiriq\BinanceManagerBundle;

use Empiriq\BinanceManagerBundle\DependencyInjection\BinanceManagerExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @api Provides repositories for interaction with the Binance state locally
 */
final class BinanceManagerBundle extends Bundle
{
    #[\Override]
    protected function getContainerExtensionClass(): string
    {
        return BinanceManagerExtension::class;
    }

    #[\Override]
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new BinanceManagerExtension();
    }

    #[\Override]
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }

    #[\Override]
    public function boot(): void
    {
    }
}
