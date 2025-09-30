<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories;

use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\FuturesCoinMRepositoryInterface;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\Synchronizers\PositionHistorySynchronizer;

class PositionHistoryRepository implements FuturesCoinMRepositoryInterface
{
    use PositionHistorySynchronizer;
}
