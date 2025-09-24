<?php

namespace Empiriq\Server\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('server');
        $treeBuilder->getRootNode()
            ->children()
            ->scalarNode('port')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('password')->end()
            ->end();

        return $treeBuilder;
    }
}
