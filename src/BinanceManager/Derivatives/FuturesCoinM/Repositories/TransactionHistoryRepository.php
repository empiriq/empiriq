<?php

namespace Empiriq\BinanceManager\Derivatives\FuturesCoinM\Repositories;

use Empiriq\BinanceManager\Common\Interfaces\Repositories\FuturesCmRepositoryInterface;
use Empiriq\BinanceManager\Derivatives\FuturesCoinM\Repositories\Synchronizers\TransactionHistorySynchronizer;

class TransactionHistoryRepository implements FuturesCmRepositoryInterface
{
    use TransactionHistorySynchronizer;
}
