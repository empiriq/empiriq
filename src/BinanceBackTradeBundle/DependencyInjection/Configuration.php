<?php

namespace Empiriq\BinanceBackTradeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    #[\Override]
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('binance_back_trade');
        $treeBuilder->getRootNode()
            ->children()
            ->scalarNode('symbol')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('start')->isRequired()->end()
            ->scalarNode('end')->isRequired()->end()
            ->end();

        return $treeBuilder;
    }
}
