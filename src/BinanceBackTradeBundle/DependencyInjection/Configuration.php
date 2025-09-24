<?php

namespace Empiriq\BinanceBackTradeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('binance_history_connector');
        $treeBuilder->getRootNode()
            ->children()
            ->scalarNode('symbol')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('start')->isRequired()->end()
            ->scalarNode('end')->isRequired()->end()
            ->end();

        return $treeBuilder;
    }
}
