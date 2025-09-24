<?php

namespace Empiriq\BinanceManager\Spot\Spot\Repositories\Synchronizers;

use Empiriq\BinanceContracts\Spot\Spot\Events\Market\TradeEvent;
use Empiriq\Contracts\Entities\TradeEntity;
use React\Promise\PromiseInterface;

use function React\Promise\resolve;

/**
 * @internal
 */
trait TradeSynchronizer
{
    public function __synchronize(): PromiseInterface
    {
        $this->dispatcher->addListener(TradeEvent::class, [$this, 'onTrade'], PHP_INT_MAX);
        return resolve([]);
    }

    public function onTrade(TradeEvent $event): void
    {
        $this->add(
            new TradeEntity(
                time: $event->time,
                symbol: $event->symbol,
                price: $event->price,
                quantity: $event->quantity,
                isBuyerMaker: $event->isBuyerMaker,
            )
        );
    }
}
