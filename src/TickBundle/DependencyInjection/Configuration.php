<?php

namespace Empiriq\TickBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    #[\Override]
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('tick');
        $root = $treeBuilder->getRootNode();
        if (!$root instanceof ArrayNodeDefinition) {
            throw new \RuntimeException('Ticker root configuration node must be an array node.');
        }
        $children = $root->children();
        $clockNode = $children->scalarNode('clock');
        $clockNode
            ->isRequired()
            ->info('Tick clock type: time (TimeDrivenClock) or event (EventDrivenClock)');

        return $treeBuilder;
    }
}
