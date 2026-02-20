<?php

namespace Empiriq\BinanceTradeBundle\DependencyInjection\Compiler;

use Empiriq\BinanceContracts\Markets\FuturesUm\FuturesUmInterface;
use Empiriq\BinanceTradeBundle\Common\Configs\RestConfig;
use Empiriq\BinanceTradeBundle\Common\Configs\WebSocketConfig;
use Empiriq\BinanceTradeBundle\Common\Helpers\Sanitizer;
use Empiriq\BinanceTradeBundle\Common\Helpers\Serializer;
use Empiriq\BinanceTradeBundle\DependencyInjection\BinanceTradeExtension;
use Empiriq\BinanceTradeBundle\Markets\FuturesCm\Clients\RestApi as FuturesCoinMRestApi;
use Empiriq\BinanceTradeBundle\Markets\FuturesCm\Clients\WsApi as FuturesCoinMWsApi;
use Empiriq\BinanceTradeBundle\Markets\FuturesCm\Clients\WsSubscriptions as FuturesCoinMWsSubscriptions;
use Empiriq\BinanceTradeBundle\Markets\FuturesCm\FuturesCm;
use Empiriq\BinanceTradeBundle\Markets\FuturesUm\Clients\RestApi as FuturesUsdMRestApi;
use Empiriq\BinanceTradeBundle\Markets\FuturesUm\Clients\WsApi as FuturesUsdMWsApi;
use Empiriq\BinanceTradeBundle\Markets\FuturesUm\Clients\WsSubscriptions as FuturesUsdMWsSubscriptions;
use Empiriq\BinanceTradeBundle\Markets\FuturesUm\FuturesUm;
use Empiriq\BinanceTradeBundle\Markets\Spot\Clients\RestApi as SpotRestApi;
use Empiriq\BinanceTradeBundle\Markets\Spot\Clients\WsApi as SpotWsApi;
use Empiriq\BinanceTradeBundle\Markets\Spot\Clients\WsSubscriptions as SpotWsSubscriptions;
use Empiriq\BinanceTradeBundle\Markets\Spot\Spot;
use Empiriq\SymfonyDependencyDiscovery\DependencyDiscovery;
use React\Http\Browser;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\HeaderUtils;

final class MarketBuildPass implements CompilerPassInterface
{
    private const TRANSPORT_MAPPINGS = [
        [
            'tag' => StreamBuildPass::TAG_FUTURES_USDM,
            'service_id' => FuturesUmInterface::class,
            'class' => FuturesUm::class,
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
            'class' => FuturesCm::class,
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
            'class' => Spot::class,
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
        if (!$container->hasParameter(BinanceTradeExtension::PARAMETER_NAME)) {
            throw new \Exception('CONFIG not found');
        }
        /** @var array<string, mixed> $config */
        $config = $container->getParameter(BinanceTradeExtension::PARAMETER_NAME);
        $defaults = $container->getParameter(BinanceTradeExtension::DEFAULTS);
        if (!is_array($defaults)) {
            throw new \RuntimeException('Invalid default endpoints config');
        }
        $environment = $config['environment'] ?? null;
        if (is_string($environment)) {
            $environment = $container->resolveEnvPlaceholders($environment);
        }
        if (!is_string($environment) || $environment === '') {
            throw new \RuntimeException('Invalid environment config');
        }
        if (!isset($defaults[$environment]) || !is_array($defaults[$environment])) {
            throw new \RuntimeException(sprintf('Default endpoints not found for environment "%s"', $environment));
        }
        if (!isset($config['endpoints']) || !is_array($config['endpoints'])) {
            $config['endpoints'] = [];
        }
        $config['endpoints'] = array_replace_recursive($defaults[$environment], $config['endpoints']);
        $this->load($config, $container);
    }

    private function load(array $config, ContainerBuilder $container): void
    {
        $container->setDefinition('empiriq.binance.serializer', new Definition(Serializer::class));
        $container->setDefinition('empiriq.binance.sanitizer', new Definition(Sanitizer::class));
        $container->setDefinition('empiriq.binance.browser', new Definition(Browser::class));

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
        $authSpec = $config['auth'] ?? '';
        if (!is_string($authSpec)) {
            throw new \RuntimeException('Auth config must be a string.');
        }
        $authSpec = ltrim($authSpec, '?');
        $auth = $authSpec === '' ? [] : HeaderUtils::parseQuery($authSpec);
        $apiKey = $auth['api_key'] ?? '';
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
                    $apiKey,
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
                    $apiKey,
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
                    $apiKey,
                    5.0,
                ]),
            ]),
            new TaggedIteratorArgument($mapping['tag']),
        ]);
    }
}
