<?php

namespace Empiriq\BinanceManager\Derivatives\FuturesUsdM\Repositories;

use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceContracts\Spot\Spot\Events\User\ExecutionReportEvent;
use Empiriq\BinanceManager\Common\Interfaces\Repositories\FuturesUmRepositoryInterface;
use Empiriq\BinanceManager\Derivatives\FuturesUsdM\Repositories\Synchronizers\OrderSynchronizer;
use Empiriq\Contracts\Entities\OrderEntity;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class OrderRepository implements FuturesUmRepositoryInterface
{
    use OrderSynchronizer;

    /**
     * @var OrderEntity[]
     */
    private array $list;

    /**
     * @param Connector $connector
     * @param EventDispatcherInterface $dispatcher
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
