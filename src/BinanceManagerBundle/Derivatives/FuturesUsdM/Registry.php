<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM;

use Empiriq\BinanceManagerBundle\Common\Interfaces\RegistryInterface;
use Empiriq\BinanceManagerBundle\Common\Interfaces\Repositories\FuturesUsdMRepositoryInterface;
use Empiriq\BinanceManagerBundle\Common\RegistryTrait;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories\BalanceRepository;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories\DepthRepository;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories\OrderHistoryRepository;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories\OrderRepository;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories\PositionHistoryRepository;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories\PositionRepository;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories\TradeHistoryRepository;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories\TradeRepository;
use Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories\TransactionHistoryRepository;

readonly class Registry implements RegistryInterface
{
    use RegistryTrait;

    /**
     * @param FuturesUsdMRepositoryInterface[] $repositories
     */
    public function __construct(
        private array $repositories,
    ) {
        foreach ($this->repositories as $repository) {
            if (!$repository instanceof FuturesUsdMRepositoryInterface) {
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
