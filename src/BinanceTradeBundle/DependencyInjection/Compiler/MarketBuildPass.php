<?php

namespace Empiriq\BinanceTradeBundle\DependencyInjection\Compiler;

use Empiriq\BinanceContracts\FuturesUmMarketInterface;
use Empiriq\BinanceTradeBundle\Common\Configs\RestConfig;
use Empiriq\BinanceTradeBundle\Common\Configs\WebSocketConfig;
use Empiriq\BinanceTradeBundle\Common\Helpers\Sanitizer;
use Empiriq\BinanceTradeBundle\Common\Helpers\Serializer;
use Empiriq\BinanceTradeBundle\Common\Signers\Ed25519Signer;
use Empiriq\BinanceTradeBundle\Common\Signers\HmacSigner;
use Empiriq\BinanceTradeBundle\Common\Signers\NullSigner;
use Empiriq\BinanceTradeBundle\Common\Signers\RsaSigner;
use Empiriq\BinanceTradeBundle\DependencyInjection\BinanceTradeExtension;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients\RestApi as FuturesCoinMRestApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients\WsApi as FuturesCoinMWsApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients\WsSubscriptions as FuturesCoinMWsSubscriptions;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\FuturesCoinMMarket;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\RestApi as FuturesUsdMRestApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WsApi as FuturesUsdMWsApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WsSubscriptions as FuturesUsdMWsSubscriptions;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\FuturesUsdMMarket;
use Empiriq\BinanceTradeBundle\Spot\Spot\Clients\RestApi as SpotRestApi;
use Empiriq\BinanceTradeBundle\Spot\Spot\Clients\WsApi as SpotWsApi;
use Empiriq\BinanceTradeBundle\Spot\Spot\Clients\WsSubscriptions as SpotWsSubscriptions;
use Empiriq\BinanceTradeBundle\Spot\Spot\SpotMarket;
use Empiriq\SymfonyDependencyDiscovery\DependencyDiscovery;
use React\Http\Browser;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class MarketBuildPass implements CompilerPassInterface
{
    private const TRANSPORT_MAPPINGS = [
        [
            'tag' => StreamBuildPass::TAG_FUTURES_USDM,
            'service_id' => FuturesUmMarketInterface::class,
            'class' => FuturesUsdMMarket::class,
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
            'class' => FuturesCoinMMarket::class,
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
            'class' => SpotMarket::class,
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
        [$apiKey, $signerDefinition] = $this->resolveAuth($config);
        $this->load($config, $apiKey, $signerDefinition, $container);
    }

    private function load(array $config, string $apiKey, Definition $signer, ContainerBuilder $container): void
    {
        $container->setDefinition('empiriq.binance.serializer', new Definition(Serializer::class));
        $container->setDefinition('empiriq.binance.sanitizer', new Definition(Sanitizer::class));
        $container->setDefinition('empiriq.binance.browser', new Definition(Browser::class));
        $container->setDefinition('empiriq.binance.signer', $signer);

        $dependencies = $this->dependency->discover($container);

        foreach (self::TRANSPORT_MAPPINGS as $mapping) {
            $hasTaggedStreams = $container->findTaggedServiceIds($mapping['tag']) !== [];
            $isRequested = in_array($mapping['class'], $dependencies, true);

            if (!$hasTaggedStreams && !$isRequested) {
                continue;
            }

            $container->setDefinition(
                $mapping['service_id'],
                $this->getTransport($config, $mapping, $apiKey)
            )->addTag('empiriq.runnable');
        }
    }

    private function getTransport(array $config, array $mapping, string $apiKey): Definition
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

    /**
     * @param array<string, mixed> $config
     * @return array{string, Definition}
     */
    private function resolveAuth(array $config): array
    {
        $auth = $config['auth'] ?? 'null';
        if (!is_string($auth) || trim($auth) === '') {
            throw new \RuntimeException('Invalid auth config');
        }

        [$type, $params] = $this->parseAuth($auth);
        $apiKey = $this->getStringParam($params, 'api_key') ?? '';

        return [$apiKey, $this->buildSignerDefinition($type, $params)];
    }

    /**
     * @param array<string, mixed> $params
     */
    private function buildSignerDefinition(string $type, array $params): Definition
    {
        return match ($type) {
            'hmac' => new Definition(HmacSigner::class, [
                $this->requireStringParam($params, 'secret', 'auth.secret')
            ]),
            'ed25519' => new Definition(Ed25519Signer::class, [
                $this->requireStringParam($params, 'private_key', 'auth.private_key'),
                $this->getStringParam($params, 'passphrase'),
            ]),
            'rsa' => new Definition(RsaSigner::class, [
                $this->requireStringParam($params, 'private_key', 'auth.private_key'),
                $this->getStringParam($params, 'passphrase'),
            ]),
            'null' => new Definition(NullSigner::class),
            default => throw new \RuntimeException(sprintf('Unsupported auth type "%s"', $type)),
        };
    }

    /**
     * @return array{0: string, 1: array<string, mixed>}
     */
    private function parseAuth(string $auth): array
    {
        $parts = explode('?', $auth, 2);
        $type = strtolower(trim($parts[0]));
        if ($type === '') {
            throw new \RuntimeException('Invalid auth type');
        }

        $params = [];
        if (isset($parts[1]) && $parts[1] !== '') {
            foreach (explode('&', $parts[1]) as $pair) {
                if ($pair === '') {
                    continue;
                }
                [$key, $value] = array_pad(explode('=', $pair, 2), 2, '');
                $params[$key] = $value;
            }
        }

        return [$type, $params];
    }

    /**
     * @param array<string, mixed> $params
     */
    private function requireStringParam(array $params, string $key, string $label): string
    {
        $value = $this->getStringParam($params, $key);
        if ($value === null || $value === '') {
            throw new \RuntimeException(sprintf('Missing required %s', $label));
        }

        return $value;
    }

    /**
     * @param array<string, mixed> $params
     */
    private function getStringParam(array $params, string $key): ?string
    {
        if (!array_key_exists($key, $params)) {
            return null;
        }
        $value = $params[$key];
        if (is_array($value)) {
            throw new \RuntimeException(sprintf('Auth parameter "%s" must be a string', $key));
        }

        return $value === null ? null : (string) $value;
    }
}
