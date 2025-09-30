<?php

namespace Empiriq\BinanceManagerBundle\Spot\Spot\Repositories\Synchronizers;

use Empiriq\BinanceContracts\Spot\Spot\Events\Market\TradeEvent;
use Empiriq\Contracts\Entities\TradeEntity;
use React\Promise\PromiseInterface;

use function React\Promise\resolve;

/**
 * @internal
 */
trait TradeSynchronizer
{
    #[\Override]
    public function __synchronize(): PromiseInterface
    {
        $this->dispatcher->addListener(TradeEvent::class, [$this, 'onTrade'], PHP_INT_MAX);
        return resolve($this);
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
