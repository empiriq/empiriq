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
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
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
        // Important: these passes must run before MessengerPass.
        // MessengerPass builds handlers locators only once from currently known
        // `messenger.message_handler` tags. If market handlers are registered later,
        // dispatching commands will fail with NoHandlerForMessageException.
        $container->addCompilerPass(
            new ResolveConfigPass(),
            PassConfig::TYPE_BEFORE_OPTIMIZATION,
            40
        );
        $container->addCompilerPass(
            new StreamBuildPass(
                new EventDiscovery([
                    new SubscriberExtractor(),
                    new ListenerExtractor(),
                ])
            ),
            PassConfig::TYPE_BEFORE_OPTIMIZATION,
            30
        );
        $container->addCompilerPass(
            new SignerBuildPass(),
            PassConfig::TYPE_BEFORE_OPTIMIZATION,
            20
        );
        $container->addCompilerPass(
            new MarketBuildPass(
                new DependencyDiscovery()
            ),
            PassConfig::TYPE_BEFORE_OPTIMIZATION,
            10
        );
    }
}
