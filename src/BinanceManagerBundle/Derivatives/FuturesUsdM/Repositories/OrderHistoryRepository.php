<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories;

use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\FuturesUsdMRepositoryInterface;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories\Synchronizers\OrderHistorySynchronizer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class OrderHistoryRepository implements FuturesUsdMRepositoryInterface
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
