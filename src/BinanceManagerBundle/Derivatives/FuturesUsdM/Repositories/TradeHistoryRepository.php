<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories;

// тут есть коммисии
use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\FuturesUsdMRepositoryInterface;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories\Synchronizers\TradeHistorySynchronizer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TradeHistoryRepository implements FuturesUsdMRepositoryInterface
{
    use TradeHistorySynchronizer;

    /**
     * @param Connector $connector
     * @param EventDispatcherInterface $dispatcher
     * @param string[] $symbols
     */
    public function __construct(
        private readonly Connector $connector,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly array $symbols
    ) {
    }
}
