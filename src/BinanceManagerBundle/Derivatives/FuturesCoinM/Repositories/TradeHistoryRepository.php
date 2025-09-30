<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories;

// тут есть коммисии
use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\FuturesCoinMRepositoryInterface;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\Synchronizers\TradeHistorySynchronizer;

class TradeHistoryRepository implements FuturesCoinMRepositoryInterface
{
    use TradeHistorySynchronizer;
}
