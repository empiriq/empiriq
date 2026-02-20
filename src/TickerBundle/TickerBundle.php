<?php

namespace Empiriq\TickerBundle;

use Empiriq\SymfonyEventDiscovery\EventDiscovery;
use Empiriq\SymfonyEventDiscovery\Extractor\ListenerExtractor;
use Empiriq\SymfonyEventDiscovery\Extractor\SubscriberExtractor;
use Empiriq\TickerBundle\DependencyInjection\Compiler\ClockBuildPass;
use Empiriq\TickerBundle\DependencyInjection\Compiler\ResolveConfigPass;
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
            new ResolveConfigPass()
        );
        $container->addCompilerPass(
            new ClockBuildPass(
                new EventDiscovery([
                    new SubscriberExtractor(),
                    new ListenerExtractor(),
                ])
            )
        );
    }
}
