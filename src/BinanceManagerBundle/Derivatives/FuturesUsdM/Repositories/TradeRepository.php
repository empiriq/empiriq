<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories;

use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\FuturesUsdMRepositoryInterface;
use Empiriq\BinanceManagerBundle\Common\RingBuffer;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories\Synchronizers\TradeSynchronizer;
use Empiriq\Contracts\Entities\TradeEntity;
use Empiriq\Contracts\Events\TradeAddedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TradeRepository implements FuturesUsdMRepositoryInterface
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
