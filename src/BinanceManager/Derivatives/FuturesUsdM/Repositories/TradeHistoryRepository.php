<?php

namespace Empiriq\BinanceManager\Derivatives\FuturesUsdM\Repositories;

// тут есть коммисии
use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceManager\Common\Interfaces\Repositories\FuturesUmRepositoryInterface;
use Empiriq\BinanceManager\Derivatives\FuturesUsdM\Repositories\Synchronizers\TradeHistorySynchronizer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TradeHistoryRepository implements FuturesUmRepositoryInterface
{
    use TradeHistorySynchronizer;

    /**
     * @param Connector $connector
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        private readonly Connector $connector,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }
}
