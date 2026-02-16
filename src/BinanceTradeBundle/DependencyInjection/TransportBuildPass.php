<?php

namespace Empiriq\BinanceTradeBundle\DependencyInjection;

use Empiriq\BinanceTradeBundle\Common\Configs\RestConfig;
use Empiriq\BinanceTradeBundle\Common\Configs\WebSocketConfig;
use Empiriq\BinanceTradeBundle\Common\Helpers\Sanitizer;
use Empiriq\BinanceTradeBundle\Common\Helpers\Serializer;
use Empiriq\BinanceTradeBundle\Common\Signers\HmacSigner;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\RestApi as FuturesUsdMRestApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WsApi as FuturesUsdMWsApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WsSubscriptions as FuturesUsdMWsSubscriptions;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients\RestApi as FuturesCoinMRestApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients\WsApi as FuturesCoinMWsApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients\WsSubscriptions as FuturesCoinMWsSubscriptions;
use Empiriq\BinanceTradeBundle\Spot\Spot\Clients\RestApi as SpotRestApi;
use Empiriq\BinanceTradeBundle\Spot\Spot\Clients\WsApi as SpotWsApi;
use Empiriq\BinanceTradeBundle\Spot\Spot\Clients\WsSubscriptions as SpotWsSubscriptions;
use Empiriq\BinanceTradeBundle\FuturesUsdMTransport;
use Empiriq\BinanceTradeBundle\FuturesCoinMTransport;
use Empiriq\BinanceTradeBundle\SpotTransport;
use Empiriq\SymfonyDependencyDiscovery\DependencyDiscovery;
use React\Http\Browser;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class TransportBuildPass implements CompilerPassInterface
{
    private const TRANSPORT_MAPPINGS = [
        [
            'tag' => StreamBuildPass::TAG_FUTURES_USDM,
            'service_id' => 'FuturesUsdMTransport',
            'class' => FuturesUsdMTransport::class,
            'endpoint_key' => 'futures_usdm',
            'clients' => [
                'rest' => FuturesUsdMRestApi::class,
                'ws' => FuturesUsdMWsApi::class,
                'subscriptions' => FuturesUsdMWsSubscriptions::class,
            ],
        ],
        [
            'tag' => StreamBuildPass::TAG_FUTURES_COINM,
            'service_id' => 'FuturesCoinMTransport',
            'class' => FuturesCoinMTransport::class,
            'endpoint_key' => 'futures_coinm',
            'clients' => [
                'rest' => FuturesCoinMRestApi::class,
                'ws' => FuturesCoinMWsApi::class,
                'subscriptions' => FuturesCoinMWsSubscriptions::class,
            ],
        ],
        [
            'tag' => StreamBuildPass::TAG_SPOT,
            'service_id' => 'SpotTransport',
            'class' => SpotTransport::class,
            'endpoint_key' => 'spot',
            'clients' => [
                'rest' => SpotRestApi::class,
                'ws' => SpotWsApi::class,
                'subscriptions' => SpotWsSubscriptions::class,
            ],
        ],
    ];

    public function __construct(
        private DependencyDiscovery $dependency
    ) {
    }

    #[\Override]
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter(BinanceTradeExtension::CONFIG)) {
            throw new \Exception('CONFIG not found');
        }
        /** @var array<string, mixed> $config */
        $config = $container->getParameter(BinanceTradeExtension::CONFIG);
        $defaults = $container->getParameter(BinanceTradeExtension::DEFAULTS);
        $config['endpoints'] = array_replace_recursive($defaults[$config['environment']], $config['endpoints'] ?? []);
        $this->load($config, $container);
    }

    private function load(array $config, ContainerBuilder $container): void
    {
        $container->setDefinition('empiriq.binance.serializer', new Definition(Serializer::class));
        $container->setDefinition('empiriq.binance.sanitizer', new Definition(Sanitizer::class));
        $container->setDefinition('empiriq.binance.browser', new Definition(Browser::class));
        $container->setDefinition(
            'empiriq.binance.signer',
            new Definition(HmacSigner::class, [
                $config['signer']['secret_key']
            ])
        );

        $dependencies = $this->dependency->discover($container);

        foreach (self::TRANSPORT_MAPPINGS as $mapping) {
            $hasTaggedStreams = $container->findTaggedServiceIds($mapping['tag']) !== [];
            $isRequested = in_array($mapping['class'], $dependencies, true);

            if (!$hasTaggedStreams && !$isRequested) {
                continue;
            }

            $container->setDefinition(
                $mapping['service_id'],
                $this->getTransport($config, $mapping)
            )->addTag('empiriq.runnable');
        }
    }

    private function getTransport(array $config, array $mapping): Definition
    {
        $endpointKey = $mapping['endpoint_key'];
        $clients = $mapping['clients'];

        return new Definition($mapping['class'], [
            new Definition($clients['rest'], [
                new Reference('empiriq.binance.signer'),
                new Reference('empiriq.binance.serializer'),
                new Reference('logger'),
                new Reference('empiriq.binance.browser'),
                new Definition(RestConfig::class, [
                    $config['endpoints'][$endpointKey]['rest_api'],
                    $config['api_key'],
                    5.0,
                ]),
            ]),
            new Definition($clients['ws'], [
                new Reference('event_dispatcher'),
                new Reference('empiriq.binance.signer'),
                new Reference('empiriq.binance.serializer'),
                new Reference('logger'),
                new Reference('empiriq.binance.sanitizer'),
                new Definition(WebSocketConfig::class, [
                    $config['endpoints'][$endpointKey]['websocket_api'],
                    $config['api_key'],
                    5.0,
                ]),
            ]),
            new Definition($clients['subscriptions'], [
                new Reference('event_dispatcher'),
                new Reference('empiriq.binance.serializer'),
                new Reference('logger'),
                new Reference('empiriq.binance.sanitizer'),
                new Definition(WebSocketConfig::class, [
                    $config['endpoints'][$endpointKey]['websocket_market_streams'],
                    $config['api_key'],
                    5.0,
                ]),
            ]),
            new TaggedIteratorArgument($mapping['tag']),
        ]);
    }
}
