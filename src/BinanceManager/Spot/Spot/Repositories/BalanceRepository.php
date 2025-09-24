<?php

namespace Empiriq\BinanceManager\Spot\Spot\Repositories;

use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceTradeBundle\Spot\Spot\Clients\WebsocketApi;
use Empiriq\BinanceManager\Common\Interfaces\Repositories\SpotRepositoryInterface;
use Empiriq\BinanceManager\Spot\Spot\Repositories\Synchronizers\BalanceSynchronizer;
use Empiriq\Contracts\Entities\BalanceEntity;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BalanceRepository implements SpotRepositoryInterface
{
    use BalanceSynchronizer;

    public const EVENT_BALANCE_ADDED = 'spot.balance.added';
    public const EVENT_BALANCE_CHANGED = 'spot.balance.changed';
    public const EVENT_BALANCE_DELETED = 'spot.balance.deleted';

    /**
     * @var BalanceEntity[]
     */
    private array $list = [];

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
