<?php

namespace Empiriq\BinanceManagerBundle\Spot\Spot;

use Empiriq\BinanceManagerBundle\Common\Interfaces\RegistryInterface;
use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\SpotRepositoryInterface;
use Empiriq\BinanceManagerBundle\Common\RegistryTrait;
use Empiriq\BinanceManagerBundle\Spot\Spot\Repositories\BalanceRepository;
use Empiriq\BinanceManagerBundle\Spot\Spot\Repositories\DepthRepository;
use Empiriq\BinanceManagerBundle\Spot\Spot\Repositories\OrderHistoryRepository;
use Empiriq\BinanceManagerBundle\Spot\Spot\Repositories\OrderRepository;
use Empiriq\BinanceManagerBundle\Spot\Spot\Repositories\PositionHistoryRepository;
use Empiriq\BinanceManagerBundle\Spot\Spot\Repositories\PositionRepository;
use Empiriq\BinanceManagerBundle\Spot\Spot\Repositories\TradeHistoryRepository;
use Empiriq\BinanceManagerBundle\Spot\Spot\Repositories\TradeRepository;
use Empiriq\BinanceManagerBundle\Spot\Spot\Repositories\TransactionHistoryRepository;

readonly class Registry implements RegistryInterface
{
    use RegistryTrait;

    /**
     * @param SpotRepositoryInterface[] $repositories
     */
    public function __construct(
        private array $repositories,
    ) {
        foreach ($this->repositories as $repository) {
            if (!$repository instanceof SpotRepositoryInterface) {
                throw new \LogicException();
            }
        }
    }

    public function balance(): BalanceRepository
    {
        return $this->getRepository(BalanceRepository::class);
    }

    public function depth(): DepthRepository
    {
        return $this->getRepository(DepthRepository::class);
    }

    public function orderHistory(): OrderHistoryRepository
    {
        return $this->getRepository(OrderHistoryRepository::class);
    }

    public function order(): OrderRepository
    {
        return $this->getRepository(OrderRepository::class);
    }

    public function positionHistory(): PositionHistoryRepository
    {
        return $this->getRepository(PositionHistoryRepository::class);
    }

    public function position(): PositionRepository
    {
        return $this->getRepository(PositionRepository::class);
    }

    public function tradeHistory(): TradeHistoryRepository
    {
        return $this->getRepository(TradeHistoryRepository::class);
    }

    public function trade(): TradeRepository
    {
        return $this->getRepository(TradeRepository::class);
    }

    public function transactionHistory(): TransactionHistoryRepository
    {
        return $this->getRepository(TransactionHistoryRepository::class);
    }
}
