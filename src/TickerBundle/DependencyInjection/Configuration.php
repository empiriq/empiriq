<?php

namespace Empiriq\TickerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    #[\Override]
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ticker');
        /** @psalm-suppress UndefinedMethod */
        $root = $treeBuilder->getRootNode();
        $root
            ->children()
                ->scalarNode('clock')
                    ->isRequired()
                    ->info('Tick clock type: time (TimeDrivenClock) or event (EventDrivenClock)')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
