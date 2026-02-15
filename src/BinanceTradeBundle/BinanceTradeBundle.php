<?php

namespace Empiriq\BinanceTradeBundle;

use Empiriq\BinanceTradeBundle\DependencyInjection\BinanceTradeExtension;
use Empiriq\BinanceTradeBundle\DependencyInjection\StreamBuildPass;
use Empiriq\BinanceTradeBundle\DependencyInjection\TransportBuildPass;
use Empiriq\SymfonyEventCollector\Collector;
use Empiriq\SymfonyEventCollector\Handler\Listener;
use Empiriq\SymfonyEventCollector\Handler\Subscriber;
use Empiriq\SymfonyInjectionCollector\Injection;
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
                new Collector([
                    new Subscriber(),
                    new Listener(),
                ])
            )
        );
        $container->addCompilerPass(
            new TransportBuildPass(
                new Injection()
            )
        );
    }
}
