<?php

namespace Empiriq\BinanceTradeBundle\DependencyInjection;

use Empiriq\BinanceTradeBundle\Common\Configs\RestApiConfig;
use Empiriq\BinanceTradeBundle\Common\Configs\WebSocketConfig;
use Empiriq\BinanceTradeBundle\Common\Helpers\Sanitizer;
use Empiriq\BinanceTradeBundle\Common\Helpers\Serializer;
use Empiriq\BinanceTradeBundle\Connector;
use React\Http\Browser;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

final class BinanceTradeExtension extends Extension
{
    #[\Override]
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $container->setDefinition('empiriq.binance.serializer', new Definition(Serializer::class));
        $container->setDefinition('empiriq.binance.sanitizer', new Definition(Sanitizer::class));
        $container->setDefinition('empiriq.binance.browser', new Definition(Browser::class));
        $container->setDefinition(
            'empiriq.binance.signer',
            new Definition($config['signer']['class'], $config['signer']['arguments'])
        );
        $container->setDefinition(
            'connector',
            new Definition(
                class: Connector::class,
                arguments: [
                    array_map(
                        callback: static fn(array $transport): Definition => new Definition(
                            class: $transport['transport_class'],
                            arguments: [
                                array_map(
                                    fn(array $stream): Definition => new Definition(
                                        $stream['class'],
                                        $stream['arguments']
                                    ),
                                    $transport['streams']
                                ),
                                new Definition($transport['rest_api_class'], [
                                    new Reference('empiriq.binance.signer'),
                                    new Reference('empiriq.binance.serializer'),
                                    new Reference('logger'),
                                    new Reference('empiriq.binance.browser'),
                                    new Definition(RestApiConfig::class, [
                                        $transport['rest_api_uri'],
                                        $config['api_key'],
                                        $config['resolver_timeout'],
                                    ]),
                                ]),
                                new Definition($transport['websocket_api_class'], [
                                    new Reference('event_dispatcher'),
                                    new Reference('empiriq.binance.signer'),
                                    new Reference('empiriq.binance.serializer'),
                                    new Reference('logger'),
                                    new Reference('empiriq.binance.sanitizer'),
                                    new Definition(WebSocketConfig::class, [
                                        $transport['websocket_api_uri'],
                                        $config['api_key'],
                                        $config['resolver_timeout'],
                                    ]),
                                ]),
                                new Definition($transport['websocket_streams_class'], [
                                    new Reference('event_dispatcher'),
                                    new Reference('empiriq.binance.serializer'),
                                    new Reference('logger'),
                                    new Reference('empiriq.binance.sanitizer'),
                                    new Definition(WebSocketConfig::class, [
                                        $transport['websocket_market_streams_uri'],
                                        $config['api_key'],
                                        $config['resolver_timeout'],
                                    ]),
                                ]),
                            ]
                        ),
                        array: $config['transports']
                    ),
                    new Reference('logger'),
                ]
            )
        )->addTag('empiriq.runnable');
    }
}
