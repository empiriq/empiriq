<?php

namespace Empiriq\BinanceTradeBundle;

use Empiriq\BinanceTradeBundle\DependencyInjection\BinanceTradeExtension;
use Empiriq\BinanceTradeBundle\DependencyInjection\BundleBuildPass;
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
            new BundleBuildPass(
                new Collector([
                    new Subscriber(),
                    new Listener(),
                ]),
                new Injection()
            )
        );
    }
}
