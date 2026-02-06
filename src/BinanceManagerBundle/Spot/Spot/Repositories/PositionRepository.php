<?php

namespace Empiriq\BinanceManagerBundle\Spot\Spot\Repositories;

use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\SpotRepositoryInterface;
use Empiriq\BinanceManagerBundle\Spot\Spot\Repositories\Synchronizers\PositionSynchronizer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PositionRepository implements SpotRepositoryInterface
{
    use PositionSynchronizer;

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
