<?php

namespace Empiriq\BinanceManager\Derivatives\FuturesUsdM\Repositories;

use Empiriq\BinanceTradeBundle\Common\Interfaces\ClientInterface;
use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Events\Market\DepthEvent;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\MarketData\Results\DepthResult;
use Empiriq\BinanceManager\Common\Interfaces\Repositories\FuturesUmRepositoryInterface;
use Empiriq\BinanceManager\Derivatives\FuturesUsdM\Repositories\Synchronizers\DepthSynchronizer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DepthRepository implements FuturesUmRepositoryInterface
{
    use DepthSynchronizer;

    /**
     * @var DepthResult|null
     */
    public ?DepthResult $result = null;

    /**
     * @var DepthEvent[]
     */
    private array $buffer = [];

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
     * @param DepthEvent $event
     * @return void
     * @internal This method is for internal use only
     */
    public function add(DepthEvent $event): void
    {
    }

    public function findAll(): DepthResult
    {
        return $this->result;
    }
}
