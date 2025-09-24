<?php

namespace Empiriq\BinanceManager\Spot\Spot\Repositories;

use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceManager\Common\Interfaces\Repositories\SpotRepositoryInterface;
use Empiriq\BinanceManager\Spot\Spot\Repositories\Synchronizers\TradeSynchronizer;
use Empiriq\Contracts\Entities\TradeEntity;
use Empiriq\Contracts\Events\TradeAddedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

class TradeRepository implements SpotRepositoryInterface
{
    use TradeSynchronizer;

    public const EVENT_TRADE_ADDED = 'spot.trade.added';

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
     * @param TradeEntity $entity
     * @return void
     */
    public function add(TradeEntity $entity): void
    {
        $this->list->add($entity);
        $this->dispatcher->dispatch(new TradeAddedEvent(), self::EVENT_TRADE_ADDED);
    }

    /**
     * @return TradeEntity[]
     */
    public function findAll(): array
    {
        return $this->list->all();
    }
}
