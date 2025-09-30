<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM;

use Empiriq\BinanceManagerBundle\Common\Interfaces\RegistryInterface;
use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\FuturesCoinMRepositoryInterface;
use Empiriq\BinanceManagerBundle\Common\RegistryTrait;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\BalanceRepository;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\DepthRepository;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\OrderHistoryRepository;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\OrderRepository;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\PositionHistoryRepository;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\PositionRepository;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\TradeHistoryRepository;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\TradeRepository;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\TransactionHistoryRepository;

readonly class Registry implements RegistryInterface
{
    use RegistryTrait;

    /**
     * @param FuturesCoinMRepositoryInterface[] $repositories
     */
    public function __construct(
        private array $repositories,
    ) {
        foreach ($this->repositories as $repository) {
            if (!$repository instanceof FuturesCoinMRepositoryInterface) {
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
