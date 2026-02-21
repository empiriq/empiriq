<?php

namespace Empiriq\BinanceRealBundle\DependencyInjection\Compiler;

use Empiriq\BinanceContracts\Markets\FuturesUm\FuturesUmInterface;
use Empiriq\BinanceRealBundle\Common\Configs\RestConfig;
use Empiriq\BinanceRealBundle\Common\Configs\WebSocketConfig;
use Empiriq\BinanceRealBundle\Common\Helpers\Sanitizer;
use Empiriq\BinanceRealBundle\Common\Helpers\Serializer;
use Empiriq\BinanceRealBundle\Common\Messaging\FuturesCmRestApiCommandMessageHandler;
use Empiriq\BinanceRealBundle\Common\Messaging\FuturesCmWsApiCommandMessageHandler;
use Empiriq\BinanceRealBundle\Common\Messaging\FuturesUmRestApiCommandMessageHandler;
use Empiriq\BinanceRealBundle\Common\Messaging\FuturesUmWsApiCommandMessageHandler;
use Empiriq\BinanceRealBundle\Common\Messaging\SpotRestApiCommandMessageHandler;
use Empiriq\BinanceRealBundle\Common\Messaging\SpotWsApiCommandMessageHandler;
use Empiriq\BinanceRealBundle\DependencyInjection\BinanceTradeExtension;
use Empiriq\BinanceRealBundle\Markets\FuturesCm\Clients\RestApi as FuturesCoinMRestApi;
use Empiriq\BinanceRealBundle\Markets\FuturesCm\Clients\WsApi as FuturesCoinMWsApi;
use Empiriq\BinanceRealBundle\Markets\FuturesCm\Clients\WsSubscriptions as FuturesCoinMWsSubscriptions;
use Empiriq\BinanceRealBundle\Markets\FuturesCm\FuturesCm;
use Empiriq\BinanceRealBundle\Markets\FuturesUm\Clients\RestApi as FuturesUsdMRestApi;
use Empiriq\BinanceRealBundle\Markets\FuturesUm\Clients\WsApi as FuturesUsdMWsApi;
use Empiriq\BinanceRealBundle\Markets\FuturesUm\Clients\WsSubscriptions as FuturesUsdMWsSubscriptions;
use Empiriq\BinanceRealBundle\Markets\FuturesUm\FuturesUm;
use Empiriq\BinanceRealBundle\Markets\Spot\Clients\RestApi as SpotRestApi;
use Empiriq\BinanceRealBundle\Markets\Spot\Clients\WsApi as SpotWsApi;
use Empiriq\BinanceRealBundle\Markets\Spot\Clients\WsSubscriptions as SpotWsSubscriptions;
use Empiriq\BinanceRealBundle\Markets\Spot\Spot;
use Empiriq\Contracts\Messaging\EventPublisherInterface;
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
            'handlers' => [
                'ws' => FuturesUmWsApiCommandMessageHandler::class,
                'rest' => FuturesUmRestApiCommandMessageHandler::class,
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
            'handlers' => [
                'ws' => FuturesCmWsApiCommandMessageHandler::class,
                'rest' => FuturesCmRestApiCommandMessageHandler::class,
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
            'handlers' => [
                'ws' => SpotWsApiCommandMessageHandler::class,
                'rest' => SpotRestApiCommandMessageHandler::class,
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

            $transportDefinition = $container->setDefinition(
                $mapping['service_id'],
                $this->getTransport($config, $mapping)
            );

            if ($hasTaggedStreams || $isRequested) {
                $transportDefinition->addTag('empiriq.runnable');
            }

            $this->registerCommandHandlers($container, $mapping);
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
                new Reference(EventPublisherInterface::class),
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
                new Reference(EventPublisherInterface::class),
                new Reference('empiriq.binance.signer'),
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

    private function registerCommandHandlers(ContainerBuilder $container, array $mapping): void
    {
        $handlers = $mapping['handlers'] ?? null;
        if (!is_array($handlers)) {
            return;
        }

        $market = new Reference($mapping['service_id']);

        if (is_string($handlers['ws'] ?? null)) {
            $container
                ->setDefinition(
                    sprintf('empiriq.binance.command_handler.%s.ws', $mapping['endpoint_key']),
                    new Definition($handlers['ws'], [$market])
                )
                ->addTag('messenger.message_handler', ['bus' => 'event.bus']);
        }

        if (is_string($handlers['rest'] ?? null)) {
            $container
                ->setDefinition(
                    sprintf('empiriq.binance.command_handler.%s.rest', $mapping['endpoint_key']),
                    new Definition($handlers['rest'], [$market])
                )
                ->addTag('messenger.message_handler', ['bus' => 'event.bus']);
        }
    }
}
