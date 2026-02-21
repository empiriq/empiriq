<?php

namespace Empiriq\BinanceRealBundle;

use Empiriq\BinanceRealBundle\DependencyInjection\BinanceTradeExtension;
use Empiriq\BinanceRealBundle\DependencyInjection\Compiler\MarketBuildPass;
use Empiriq\BinanceRealBundle\DependencyInjection\Compiler\ResolveConfigPass;
use Empiriq\BinanceRealBundle\DependencyInjection\Compiler\SignerBuildPass;
use Empiriq\BinanceRealBundle\DependencyInjection\Compiler\StreamBuildPass;
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
final class BinanceRealBundle extends Bundle
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
            new SignerBuildPass()
        );
        $container->addCompilerPass(
            new MarketBuildPass(
                new DependencyDiscovery()
            )
        );
    }
}
