<?php

namespace Empiriq\BinanceManager\Derivatives\FuturesUsdM\Repositories;

use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceManager\Common\Interfaces\Repositories\FuturesUmRepositoryInterface;
use Empiriq\BinanceManager\Derivatives\FuturesUsdM\Repositories\Synchronizers\BalanceSynchronizer;
use Empiriq\Contracts\Entities\BalanceEntity;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BalanceRepository implements FuturesUmRepositoryInterface
{
    use BalanceSynchronizer;

    public const EVENT_BALANCE_ADDED = 'futures_um.balance.added';
    public const EVENT_BALANCE_CHANGED = 'futures_um.balance.changed';
    public const EVENT_BALANCE_DELETED = 'futures_um.balance.deleted';

    /**
     * @var BalanceEntity[]
     */
    private array $list;

    /**
     * @param Connector $connector
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        private readonly Connector $connector,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    /**
     * @return BalanceEntity[]
     */
    public function findAll(): array
    {
        return $this->list;
    }

    /**
     * @param string $asset
     * @return BalanceEntity|null
     */
    public function findOneByAsset(string $asset): ?BalanceEntity
    {
        return array_find($this->list, fn(BalanceEntity $entity) => $entity->asset === $asset);
    }

    /**
     * @param string $asset
     * @return bool
     */
    public function existsByAsset(string $asset): bool
    {
        return array_any($this->list, fn(BalanceEntity $entity) => $entity->asset === $asset);
    }
}
