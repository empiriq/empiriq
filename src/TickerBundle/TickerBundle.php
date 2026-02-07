<?php

namespace Empiriq\TickerBundle;

use Empiriq\SymfonyEventCollector\Collector;
use Empiriq\SymfonyEventCollector\Handler\Listener;
use Empiriq\SymfonyEventCollector\Handler\Subscriber;
use Empiriq\TickerBundle\DependencyInjection\BundleBuildPass;
use Empiriq\TickerBundle\DependencyInjection\TickerExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class TickerBundle extends Bundle
{
    #[\Override]
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new TickerExtension();
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
                ])
            )
        );
    }
}
