<?php

namespace Empiriq\BinanceManagerBundle\Spot\Spot\Repositories;

use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\SpotRepositoryInterface;
use Empiriq\BinanceManagerBundle\Spot\Spot\Repositories\Synchronizers\PositionHistorySynchronizer;
use Psr\EventDispatcher\EventDispatcherInterface;

class PositionHistoryRepository implements SpotRepositoryInterface
{
    use PositionHistorySynchronizer;

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
