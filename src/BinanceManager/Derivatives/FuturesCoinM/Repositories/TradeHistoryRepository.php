<?php

namespace Empiriq\BinanceManager\Derivatives\FuturesCoinM\Repositories;

// тут есть коммисии
use Empiriq\BinanceManager\Common\Interfaces\Repositories\FuturesCmRepositoryInterface;
use Empiriq\BinanceManager\Derivatives\FuturesCoinM\Repositories\Synchronizers\TradeHistorySynchronizer;

class TradeHistoryRepository implements FuturesCmRepositoryInterface
{
    use TradeHistorySynchronizer;
}
