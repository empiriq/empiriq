<?php

namespace Empiriq\BinanceManagerBundle\Spot\Spot\Repositories;

// тут есть коммисии
use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\SpotRepositoryInterface;
use Empiriq\BinanceManagerBundle\Spot\Spot\Repositories\Synchronizers\TradeHistorySynchronizer;
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
