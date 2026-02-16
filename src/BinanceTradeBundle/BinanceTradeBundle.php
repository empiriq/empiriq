<?php

namespace Empiriq\BinanceTradeBundle;

use Empiriq\BinanceTradeBundle\DependencyInjection\BinanceTradeExtension;
use Empiriq\BinanceTradeBundle\DependencyInjection\StreamBuildPass;
use Empiriq\BinanceTradeBundle\DependencyInjection\TransportBuildPass;
use Empiriq\SymfonyEventDiscovery\EventDiscovery;
use Empiriq\SymfonyEventDiscovery\Extractor\ListenerExtractor;
use Empiriq\SymfonyEventDiscovery\Extractor\SubscriberExtractor;
use Empiriq\SymfonyDependencyDiscovery\DependencyDiscovery;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @api Provides interaction with the Binance exchange
 */
final class BinanceTradeBundle extends Bundle
{
    #[\Override]
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new BinanceTradeExtension();
    }

    #[\Override]
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(
            new StreamBuildPass(
                new EventDiscovery([
                    new SubscriberExtractor(),
                    new ListenerExtractor(),
                ])
            )
        );
        $container->addCompilerPass(
            new TransportBuildPass(
                new DependencyDiscovery()
            )
        );
    }
}
