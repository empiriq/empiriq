<?php

namespace Empiriq\BinanceRealBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    #[\Override]
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('binance_real');
        /** @psalm-suppress UndefinedMethod */
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('auth')
                    ->defaultValue('null')
                ->end()
                ->scalarNode('environment')
                    ->defaultValue('mainnet')
                ->end()
                ->arrayNode('endpoints')
                    ->children()
                        ->arrayNode('spot')
                            ->children()
                                ->scalarNode('rest_api')->defaultNull()->end()
                                ->scalarNode('websocket_api')->defaultNull()->end()
                                ->scalarNode('websocket_market_streams')->defaultNull()->end()
                            ->end()
                        ->end()
                        ->arrayNode('futures_usdm')
                            ->children()
                                ->scalarNode('rest_api')->defaultNull()->end()
                                ->scalarNode('websocket_api')->defaultNull()->end()
                                ->scalarNode('websocket_market_streams')->defaultNull()->end()
                            ->end()
                        ->end()
                        ->arrayNode('futures_coinm')
                            ->children()
                                ->scalarNode('rest_api')->defaultNull()->end()
                                ->scalarNode('websocket_api')->defaultNull()->end()
                                ->scalarNode('websocket_market_streams')->defaultNull()->end()
                            ->end()
                        ->end()
                        ->arrayNode('options')
                            ->children()
                                ->scalarNode('rest_api')->defaultNull()->end()
                                ->scalarNode('websocket_api')->defaultNull()->end()
                                ->scalarNode('websocket_market_streams')->defaultNull()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
