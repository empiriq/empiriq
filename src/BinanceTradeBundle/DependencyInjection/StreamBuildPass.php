<?php

namespace Empiriq\BinanceTradeBundle\DependencyInjection;

use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Streams\TradeStream;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Streams\UserDataStream;
use Empiriq\SymfonyEventCollector\Collector;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class StreamBuildPass implements CompilerPassInterface
{
    public function __construct(
        private Collector $collector
    ) {
    }

    #[\Override]
    public function process(ContainerBuilder $container): void
    {
        $container->setDefinition(
            'empiriq.binance.futures_usdm.stream.trade',
            new Definition(TradeStream::class, [
                ['BTCUSDT'],
            ])
        )->addTag('empiriq.binance.futures_usdm.stream');

//        $container->setDefinition(
//            'empiriq.binance.futures_usdm.stream.user_data',
//            new Definition(UserDataStream::class)
//        )->addTag('empiriq.binance.futures_usdm.stream');
    }
}
