<?php

namespace Empiriq\BinanceTradeBundle;

use Empiriq\BinanceTradeBundle\DependencyInjection\BinanceTradeExtension;
use Empiriq\BinanceTradeBundle\DependencyInjection\Compiler\MarketBuildPass;
use Empiriq\BinanceTradeBundle\DependencyInjection\Compiler\ResolveConfigPass;
use Empiriq\BinanceTradeBundle\DependencyInjection\Compiler\StreamBuildPass;
use Empiriq\SymfonyDependencyDiscovery\DependencyDiscovery;
use Empiriq\SymfonyEventDiscovery\EventDiscovery;
use Empiriq\SymfonyEventDiscovery\Extractor\ListenerExtractor;
use Empiriq\SymfonyEventDiscovery\Extractor\SubscriberExtractor;
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
            new ResolveConfigPass()
        );
        $container->addCompilerPass(
            new StreamBuildPass(
                new EventDiscovery([
                    new SubscriberExtractor(),
                    new ListenerExtractor(),
                ])
            )
        );
        $container->addCompilerPass(
            new MarketBuildPass(
                new DependencyDiscovery()
            )
        );
    }
}
