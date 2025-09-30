<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories;

use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceContracts\Spot\Spot\Events\User\ExecutionReportEvent;
use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\FuturesUsdMRepositoryInterface;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories\Synchronizers\OrderSynchronizer;
use Empiriq\Contracts\Entities\OrderEntity;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class OrderRepository implements FuturesUsdMRepositoryInterface
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
