<?php

namespace Empiriq\BinanceManager\Spot\Spot\Repositories;

use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceContracts\Spot\Spot\Events\Market\DepthEvent;
use Empiriq\BinanceManager\Common\Interfaces\Repositories\SpotRepositoryInterface;
use Empiriq\BinanceManager\Spot\Spot\Repositories\Synchronizers\DepthSynchronizer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * https://developers.binance.com/docs/binance-spot-api-docs/websocket-api/market-data-requests#order-book
 * https://developers.binance.com/docs/binance-spot-api-docs/web-socket-streams#how-to-manage-a-local-order-book-correctly
 */
class DepthRepository implements SpotRepositoryInterface
{
    use DepthSynchronizer;

    /**
     * @var DepthEvent[]
     */
    private array $buffer = [];

    /**
     * @param Connector $connector
     * @param EventDispatcherInterface $dispatcher
     * @param string[] $symbols
     */
    public function __construct(
        private readonly Connector $connector,
        private readonly EventDispatcherInterface $dispatcher,
        private array $symbols,
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
}
