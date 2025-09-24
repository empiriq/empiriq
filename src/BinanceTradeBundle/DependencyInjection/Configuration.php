<?php

namespace Empiriq\BinanceTradeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('binance_api_connector');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->scalarNode('api_key')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('api_secret')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('rest_api_uri')->defaultValue('https://fapi.binance.com')->end()
                ->scalarNode('websocket_api_uri')->defaultValue('wss://ws-fapi.binance.com/ws-fapi/v1')->end()
                ->scalarNode('websocket_market_streams_uri')->defaultValue('wss://fstream.binance.com/ws')->end()
                ->integerNode('resolver_timeout')->defaultValue(10)->end()
                ->arrayNode('symbols')->prototype('scalar')->end()->defaultValue(['BTCUSDT'])->end()
            ->end();

        return $treeBuilder;
    }
}
