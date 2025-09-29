<?php

namespace Empiriq\BinanceTradeBundle\DependencyInjection;

use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\RestApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WebsocketApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WebsocketStreams;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\FuturesUsdMTransport;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Streams\TradeStream;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    #[\Override]
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('binance_trade');
        /** @psalm-suppress UndefinedMethod */
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('api_key')->isRequired()->end()
                ->scalarNode('api_secret')->isRequired()->end()
                ->integerNode('resolver_timeout')->defaultValue(10)->end()
                ->arrayNode('transports')
                    ->isRequired()
                    ->children()

                        ->arrayNode('futures_usd')
                            ->children()
                                ->scalarNode('transport_class')
                                    ->defaultValue(FuturesUsdMTransport::class)->end()
                                ->scalarNode('rest_api_class')
                                    ->defaultValue(RestApi::class)->end()
                                ->scalarNode('rest_api_uri')
                                    ->defaultValue('https://fapi.binance.com')->end()
                                ->scalarNode('websocket_api_class')
                                    ->defaultValue(WebsocketApi::class)->end()
                                ->scalarNode('websocket_api_uri')
                                    ->defaultValue('wss://ws-fapi.binance.com/ws-fapi/v1')->end()
                                ->scalarNode('websocket_streams_class')
                                    ->defaultValue(WebsocketStreams::class)->end()
                                ->scalarNode('websocket_market_streams_uri')
                                    ->defaultValue('wss://fstream.binance.com/ws')->end()
                                ->arrayNode('streams')
                                    ->children()
                                        ->arrayNode('trade')
                                            ->beforeNormalization()
                                                ->always(
                                                    fn ($v) => is_array($v) && array_is_list($v)
                                                    ? ['arguments' => $v] : $v
                                                )
                                            ->end()
                                            ->children()
                                                ->scalarNode('class')
                                                    ->defaultValue(TradeStream::class)->cannotBeEmpty()->end()
                                                ->arrayNode('arguments')
                                                    ->prototype('variable')->end()->defaultValue([])->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()

                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
