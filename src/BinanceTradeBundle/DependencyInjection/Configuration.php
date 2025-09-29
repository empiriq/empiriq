<?php

namespace Empiriq\BinanceTradeBundle\DependencyInjection;

use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\RestApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WebsocketApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WebsocketStreams;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\FuturesUsdMTransport;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Streams\TradeStream;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    #[\Override]
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('binance_trade');
        /** @psalm-suppress UndefinedMethod */
        $root = $treeBuilder->getRootNode();
        $root
            ->children()
                ->scalarNode('api_key')->defaultNull()->end()
                ->scalarNode('api_secret')->defaultNull()->end()
                ->integerNode('resolver_timeout')->defaultValue(10)->end()
                ->arrayNode('transports')
                    ->isRequired()
                    ->children()
                        ->append($this->addTransportNode('futures_usd', [
                            'transport_class'              => FuturesUsdMTransport::class,
                            'rest_api_class'               => RestApi::class,
                            'rest_api_uri'                 => 'https://fapi.binance.com',
                            'websocket_api_class'          => WebsocketApi::class,
                            'websocket_api_uri'            => 'wss://ws-fapi.binance.com/ws-fapi/v1',
                            'websocket_streams_class'      => WebsocketStreams::class,
                            'websocket_market_streams_uri' => 'wss://fstream.binance.com/ws',
                            'streams' => [
                                'trade' => TradeStream::class,
                            ],
                        ]))
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

    private function addTransportNode(string $name, array $defaults): NodeDefinition
    {
        $builder = new TreeBuilder($name);
        /** @psalm-suppress UndefinedMethod */
        $node = $builder->getRootNode();
        $node
            ->children()
                ->scalarNode('transport_class')
                    ->defaultValue($defaults['transport_class'] ?? FuturesUsdMTransport::class)
                ->end()
                ->scalarNode('rest_api_class')
                    ->defaultValue($defaults['rest_api_class'] ?? RestApi::class)
                ->end()
                ->scalarNode('rest_api_uri')
                    ->defaultValue($defaults['rest_api_uri'] ?? 'https://fapi.binance.com')
                ->end()
                ->scalarNode('websocket_api_class')
                    ->defaultValue($defaults['websocket_api_class'] ?? WebsocketApi::class)
                ->end()
                ->scalarNode('websocket_api_uri')
                    ->defaultValue($defaults['websocket_api_uri'] ?? 'wss://ws-fapi.binance.com/ws-fapi/v1')
                ->end()
                ->scalarNode('websocket_streams_class')
                    ->defaultValue($defaults['websocket_streams_class'] ?? WebsocketStreams::class)
                ->end()
                ->scalarNode('websocket_market_streams_uri')
                    ->defaultValue($defaults['websocket_market_streams_uri'] ?? 'wss://fstream.binance.com/ws')
                ->end()
                ->arrayNode('streams')
                    ->children()
                    // append stream nodes
                    ->end()
                ->end()
            ->end();

        $streamsChildren = $node->find('streams')->children();
        foreach ($defaults['streams'] ?? [] as $streamName => $streamClass) {
            $streamsChildren->append($this->addStreamNode($streamName, (string)$streamClass));
        }

        return $node;
    }

    private function addStreamNode(string $name, string $class): NodeDefinition
    {
        $builder = new TreeBuilder($name);
        /** @psalm-suppress UndefinedMethod */
        $node = $builder->getRootNode();

        $node
            ->beforeNormalization()
                ->always(fn ($v) => is_array($v) && array_is_list($v) ? ['arguments' => $v] : $v)
            ->end()
            ->children()
                ->scalarNode('class')
                    ->defaultValue($class)
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('arguments')
                    ->prototype('variable')->end()
                    ->defaultValue([])
                ->end()
            ->end();

        return $node;
    }
}
