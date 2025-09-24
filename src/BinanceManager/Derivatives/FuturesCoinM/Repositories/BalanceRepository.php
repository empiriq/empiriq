<?php

namespace Empiriq\BinanceManager\Derivatives\FuturesCoinM\Repositories;

use Empiriq\BinanceManager\Common\Interfaces\Repositories\FuturesCmRepositoryInterface;
use Empiriq\BinanceManager\Derivatives\FuturesCoinM\Repositories\Synchronizers\BalanceSynchronizer;

class BalanceRepository implements FuturesCmRepositoryInterface
{
    use BalanceSynchronizer;

    public const EVENT_FUTURES_CM_BALANCE_ADDED = 'futures_cm.balance.added';
    public const EVENT_FUTURES_CM_BALANCE_CHANGED = 'futures_cm.balance.changed';
    public const EVENT_FUTURES_CM_BALANCE_DELETED = 'futures_cm.balance.deleted';
}
