<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories;

use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\FuturesCoinMRepositoryInterface;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\Synchronizers\TransactionHistorySynchronizer;

class TransactionHistoryRepository implements FuturesCoinMRepositoryInterface
{
    use TransactionHistorySynchronizer;
}
