<?php

namespace Empiriq\BinanceManager\Derivatives\FuturesUsdM\Repositories;

use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceManager\Common\Interfaces\Repositories\FuturesUmRepositoryInterface;
use Empiriq\BinanceManager\Derivatives\FuturesUsdM\Repositories\Synchronizers\OrderHistorySynchronizer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class OrderHistoryRepository implements FuturesUmRepositoryInterface
{
    use OrderHistorySynchronizer;

    /**
     * @param Connector $connector
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        private readonly Connector $connector,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    public function add(): void
    {
    }

    public function find($id): void
    {
    }

    public function findAll(): void
    {
    }

    public function findBy(array $criteria): void
    {
    }

    public function findOneBy(array $criteria): void
    {
    }

    public function exists($id): void
    {
    }

    public function update($entity): void
    {
    }

    public function delete($entity): void
    {
    }

    public function deleteById($entity): void
    {
    }
}
