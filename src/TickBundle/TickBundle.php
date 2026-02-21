<?php

namespace Empiriq\TickBundle;

use Empiriq\SymfonyEventDiscovery\EventDiscovery;
use Empiriq\SymfonyEventDiscovery\Extractor\ListenerExtractor;
use Empiriq\SymfonyEventDiscovery\Extractor\SubscriberExtractor;
use Empiriq\TickBundle\DependencyInjection\Compiler\ClockBuildPass;
use Empiriq\TickBundle\DependencyInjection\Compiler\ResolveConfigPass;
use Empiriq\TickBundle\DependencyInjection\TickExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Symfony bundle entrypoint for ticker infrastructure.
 *
 * @api
 */
final class TickBundle extends Bundle
{
    #[\Override]
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new TickExtension();
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
