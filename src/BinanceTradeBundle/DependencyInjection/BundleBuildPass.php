<?php

namespace Empiriq\BinanceTradeBundle\DependencyInjection;

use Empiriq\BinanceTradeBundle\Common\Configs\RestConfig;
use Empiriq\BinanceTradeBundle\Common\Configs\WebSocketConfig;
use Empiriq\BinanceTradeBundle\Common\Helpers\Sanitizer;
use Empiriq\BinanceTradeBundle\Common\Helpers\Serializer;
use Empiriq\BinanceTradeBundle\Common\Signers\HmacSigner;
use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\RestApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WebSocketApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WebSocketStreams;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\FuturesUsdMTransport;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Streams\TradeStream;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Streams\UserDataStream;
use Empiriq\SymfonyEventCollector\Collector;
use React\Http\Browser;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class BundleBuildPass implements CompilerPassInterface
{
    public function __construct(
        private Collector $collector
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
        $container->setDefinition(
            'connector',
            $this->getConnector($config)
        )->addTag('empiriq.runnable');
    }

    private function getConnector(array $config): Definition
    {
        $transportDefinition = new Definition(FuturesUsdMTransport::class, [
            [
                new Definition(TradeStream::class, [
                    ['BTCUSDT']
                ]),
//                new Definition(UserDataStream::class),
            ],
            new Definition(RestApi::class, [
                new Reference('empiriq.binance.signer'),
                new Reference('empiriq.binance.serializer'),
                new Reference('logger'),
                new Reference('empiriq.binance.browser'),
                new Definition(RestConfig::class, [
                    $config['endpoints']['futures_usdm']['rest_api'],
                    $config['api_key'],
                    5.0,
                ]),
            ]),
            new Definition(WebSocketApi::class, [
                new Reference('event_dispatcher'),
                new Reference('empiriq.binance.signer'),
                new Reference('empiriq.binance.serializer'),
                new Reference('logger'),
                new Reference('empiriq.binance.sanitizer'),
                new Definition(WebSocketConfig::class, [
                    $config['endpoints']['futures_usdm']['websocket_api'],
                    $config['api_key'],
                    5.0,
                ]),
            ]),
            new Definition(WebSocketStreams::class, [
                new Reference('event_dispatcher'),
                new Reference('empiriq.binance.serializer'),
                new Reference('logger'),
                new Reference('empiriq.binance.sanitizer'),
                new Definition(WebSocketConfig::class, [
                    $config['endpoints']['futures_usdm']['websocket_market_streams'],
                    $config['api_key'],
                    5.0,
                ]),
            ]),
        ]);

        return new Definition(Connector::class, [
            [
                $transportDefinition,
            ],
            new Reference('logger'),
        ]);
    }
}
