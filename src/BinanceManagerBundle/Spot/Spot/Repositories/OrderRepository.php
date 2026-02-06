<?php

namespace Empiriq\BinanceManagerBundle\Spot\Spot\Repositories;

use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceContracts\Spot\Spot\Events\User\ExecutionReportEvent;
use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\SpotRepositoryInterface;
use Empiriq\BinanceManagerBundle\Spot\Spot\Repositories\Synchronizers\OrderSynchronizer;
use Empiriq\Contracts\Entities\OrderEntity;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class OrderRepository implements SpotRepositoryInterface
{
    use OrderSynchronizer;

    /**
     * @var OrderEntity[]
     */
    private array $list;

    /**
     * @param Connector $connector
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    public function __construct(
        private readonly Connector $connector,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    /**
     * @param ExecutionReportEvent $event
     * @return void
     */
    public static function update(ExecutionReportEvent $event): void
    {
    }
}
