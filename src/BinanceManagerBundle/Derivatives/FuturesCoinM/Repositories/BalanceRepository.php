<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories;

use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\FuturesCoinMRepositoryInterface;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\Synchronizers\BalanceSynchronizer;

class BalanceRepository implements FuturesCoinMRepositoryInterface
{
    use BalanceSynchronizer;

    public const EVENT_FUTURES_CM_BALANCE_ADDED = 'futures_cm.balance.added';
    public const EVENT_FUTURES_CM_BALANCE_CHANGED = 'futures_cm.balance.changed';
    public const EVENT_FUTURES_CM_BALANCE_DELETED = 'futures_cm.balance.deleted';
}
