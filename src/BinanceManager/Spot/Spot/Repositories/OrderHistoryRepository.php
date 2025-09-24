<?php

namespace Empiriq\BinanceManager\Spot\Spot\Repositories;

use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceManager\Common\Interfaces\Repositories\SpotRepositoryInterface;
use Empiriq\BinanceManager\Spot\Spot\Repositories\Synchronizers\OrderHistorySynchronizer;
use Psr\EventDispatcher\EventDispatcherInterface;

class OrderHistoryRepository implements SpotRepositoryInterface
{
    use OrderHistorySynchronizer;

    /**
     * @param Connector $connector
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
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
