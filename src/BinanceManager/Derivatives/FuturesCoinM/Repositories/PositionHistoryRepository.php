<?php

namespace Empiriq\BinanceManager\Derivatives\FuturesCoinM\Repositories;

use Empiriq\BinanceManager\Common\Interfaces\Repositories\FuturesCmRepositoryInterface;
use Empiriq\BinanceManager\Derivatives\FuturesCoinM\Repositories\Synchronizers\PositionHistorySynchronizer;

class PositionHistoryRepository implements FuturesCmRepositoryInterface
{
    use PositionHistorySynchronizer;
}
