<?php

namespace Empiriq\BinanceTradeBundle\DependencyInjection;

use Empiriq\BinanceTradeBundle\Common\Helpers\Sanitizer;
use Empiriq\BinanceTradeBundle\Common\Helpers\Serializer;
use Empiriq\BinanceTradeBundle\Common\Signers\HmacSigner;
use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\RestApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WebsocketApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WebsocketStreams;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\FuturesUsdMTransport;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Streams\TradeStream;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

final class BinanceApiConnectorExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $apiKey         = $config['api_key'];
        $apiSecret      = $config['api_secret'];
        $restApiUri     = $config['rest_api_uri'];
        $wsApiUri       = $config['websocket_api_uri'];
        $wsStreamsUri   = $config['websocket_market_streams_uri'];
        $resolverTimeout = $config['resolver_timeout'] ?? 10;

        $logger = new Reference(\Psr\Log\LoggerInterface::class);

        // --- базовые сервисы ---
        $container->setDefinition(Serializer::class, new Definition(Serializer::class));
        $container->setDefinition(Sanitizer::class, new Definition(Sanitizer::class));
        $container->setDefinition(HmacSigner::class, new Definition(HmacSigner::class, [
            $apiSecret,
        ]));

        // --- Streams ---
        $container->setDefinition(TradeStream::class, new Definition(TradeStream::class, [
            ['BTCUSDT'], // TODO: to config
        ]));

        // --- Клиенты ---
        $container->setDefinition(RestApi::class, (new Definition(RestApi::class, [
            $restApiUri,
            $apiKey,
            new Reference(HmacSigner::class),
            new Reference(Serializer::class),
            $logger,
            new Reference(Sanitizer::class),
            $resolverTimeout,
        ]))->setAutowired(true)->setAutoconfigured(true));

        $container->setDefinition(WebsocketApi::class, (new Definition(WebsocketApi::class, [
            new Reference('event_dispatcher'),
            $wsApiUri,
            $apiKey,
            new Reference(HmacSigner::class),
            new Reference(Serializer::class),
            $logger,
            new Reference(Sanitizer::class),
            $resolverTimeout,
        ]))->setAutowired(true)->setAutoconfigured(true));

        $container->setDefinition(WebsocketStreams::class, (new Definition(WebsocketStreams::class, [
            new Reference('event_dispatcher'),
            $wsStreamsUri,
            new Reference(Serializer::class),
            $logger,
            new Reference(Sanitizer::class),
            $resolverTimeout,
        ]))->setAutowired(true)->setAutoconfigured(true));

        // --- Transport ---
        $container->setDefinition(FuturesUsdMTransport::class, (new Definition(FuturesUsdMTransport::class, [
            [new Reference(TradeStream::class)],
            new Reference(RestApi::class),
            new Reference(WebsocketApi::class),
            new Reference(WebsocketStreams::class),
        ]))->setAutowired(true)->setAutoconfigured(true));

        // --- Connector ---
        $container->setDefinition(Connector::class, (new Definition(Connector::class, [
            [new Reference(FuturesUsdMTransport::class)],
            $logger,
        ]))->setPublic(true)->setAutowired(true)->setAutoconfigured(true));
    }
}
