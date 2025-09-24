<?php

namespace Empiriq\BinanceManager\Derivatives\FuturesUsdM\Repositories;

use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceManager\Common\Interfaces\Repositories\FuturesUmRepositoryInterface;
use Empiriq\BinanceManager\Common\RingBuffer;
use Empiriq\BinanceManager\Derivatives\FuturesUsdM\Repositories\Synchronizers\TradeSynchronizer;
use Empiriq\Contracts\Entities\TradeEntity;
use Empiriq\Contracts\Events\TradeAddedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TradeRepository implements FuturesUmRepositoryInterface
{
    use TradeSynchronizer;

    public const EVENT_TRADE_ADDED = 'futures_um.trade.added';

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
