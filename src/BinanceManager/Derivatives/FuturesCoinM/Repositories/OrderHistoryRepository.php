<?php

namespace Empiriq\BinanceManager\Derivatives\FuturesCoinM\Repositories;

use Empiriq\BinanceManager\Common\Interfaces\Repositories\FuturesCmRepositoryInterface;
use Empiriq\BinanceManager\Derivatives\FuturesCoinM\Repositories\Synchronizers\OrderHistorySynchronizer;

class OrderHistoryRepository implements FuturesCmRepositoryInterface
{
    use OrderHistorySynchronizer;
}
