<?php

use Empiriq\BinanceBackTradeBundle\Common\Helpers\Serializer;
use Empiriq\BinanceBackTradeBundle\Connector;
use Empiriq\BinanceBackTradeBundle\Spot\Spot\Receivers\SpotReceiver;
use Empiriq\BinanceBackTradeBundle\Spot\Spot\Receivers\Streams\Spot\TradeStream;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;

return static function (ContainerBuilder $container): void {
    // 1. Базовые сервисы
    $container->register(EventDispatcher::class, EventDispatcher::class);
    $container->register(Serializer::class, Serializer::class);

    // 2. Connector
    $container->register(Connector::class, Connector::class)
        ->addArgument(new Reference(Serializer::class))
        ->addArgument(new Reference(EventDispatcher::class))
        ->setPublic(true);

    // 3. Market + Stream
    $container->register(SpotReceiver::class, SpotReceiver::class);

    $container->register(TradeStream::class, TradeStream::class)
        ->addArgument('BTCUSDT')
        ->addArgument(new DateTimeImmutable('2024-01-01'))
        ->addArgument(new DateTimeImmutable('2024-01-03')); // не включительно

    // 4. Сборка связей
    $container->getDefinition(SpotReceiver::class)
        ->addMethodCall('addStream', [new Reference(TradeStream::class)]);

    $container->getDefinition(Connector::class)
        ->addMethodCall('addMarket', [new Reference(SpotReceiver::class)]);
};
