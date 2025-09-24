<?php

namespace Empiriq\BinanceManager\Derivatives\FuturesCoinM;

use Empiriq\BinanceManager\Common\Interfaces\RegistryInterface;
use Empiriq\BinanceManager\Common\Interfaces\Repositories\FuturesCmRepositoryInterface;
use Empiriq\BinanceManager\Common\RegistryTrait;
use Empiriq\BinanceManager\Derivatives\FuturesCoinM\Repositories\BalanceRepository;
use Empiriq\BinanceManager\Derivatives\FuturesCoinM\Repositories\DepthRepository;
use Empiriq\BinanceManager\Derivatives\FuturesCoinM\Repositories\OrderHistoryRepository;
use Empiriq\BinanceManager\Derivatives\FuturesCoinM\Repositories\OrderRepository;
use Empiriq\BinanceManager\Derivatives\FuturesCoinM\Repositories\PositionHistoryRepository;
use Empiriq\BinanceManager\Derivatives\FuturesCoinM\Repositories\PositionRepository;
use Empiriq\BinanceManager\Derivatives\FuturesCoinM\Repositories\TradeHistoryRepository;
use Empiriq\BinanceManager\Derivatives\FuturesCoinM\Repositories\TradeRepository;
use Empiriq\BinanceManager\Derivatives\FuturesCoinM\Repositories\TransactionHistoryRepository;

readonly class Registry implements RegistryInterface
{
    use RegistryTrait;

    /**
     * @param FuturesCmRepositoryInterface[] $repositories
     */
    public function __construct(
        private array $repositories,
    ) {
        foreach ($this->repositories as $repository) {
            //todo  FuturesCmRepositoryInterface ? RepositoryInterface
            if (!$repository instanceof FuturesCmRepositoryInterface) {
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
