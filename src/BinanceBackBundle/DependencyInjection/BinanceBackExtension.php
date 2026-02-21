<?php

namespace Empiriq\BinanceBackBundle\DependencyInjection;

use Empiriq\BinanceBackBundle\Common\Helpers\Serializer;
use Empiriq\BinanceBackBundle\Connector;
use Empiriq\BinanceBackBundle\Spot\Spot\Receivers\SpotReceiver;
use Empiriq\BinanceBackBundle\Spot\Spot\Receivers\Streams\Spot\TradeStream;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

final class BinanceBackExtension extends Extension
{
    #[\Override]
    public function load(array $configs, ContainerBuilder $container)
    {
        // 1. Читаем конфиг
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // 2. Базовые сервисы
        $container->register(Serializer::class, Serializer::class);

        // 3. Connector
        $container->register(Connector::class, Connector::class)
            ->addArgument(new Reference(Serializer::class))
            ->addArgument(new Reference('event_dispatcher'))
            ->setPublic(true);

        // 4. Market + Stream
        $container->register(SpotReceiver::class, SpotReceiver::class);

        $container->register(TradeStream::class, TradeStream::class)
            ->addArgument($config['symbol'])
            ->addArgument($config['start'])
            ->addArgument($config['end']);

        // 5. Сборка связей
        $container->getDefinition(SpotReceiver::class)
            ->addMethodCall('addStream', [new Reference(TradeStream::class)]);

        $container->getDefinition(Connector::class)
            ->addMethodCall('addMarket', [new Reference(SpotReceiver::class)]);
    }
}
