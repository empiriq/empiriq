<?php

namespace Empiriq\BinanceManager\Spot\Spot\Repositories;

// тут есть коммисии
use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceManager\Common\Interfaces\Repositories\SpotRepositoryInterface;
use Empiriq\BinanceManager\Spot\Spot\Repositories\Synchronizers\TradeHistorySynchronizer;
use Psr\EventDispatcher\EventDispatcherInterface;

class TradeHistoryRepository implements SpotRepositoryInterface
{
    use TradeHistorySynchronizer;

    /**
     * @param Connector $connector
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    public function __construct(
        private readonly Connector $connector,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }
}
