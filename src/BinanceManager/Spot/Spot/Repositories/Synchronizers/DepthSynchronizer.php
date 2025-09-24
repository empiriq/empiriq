<?php

namespace Empiriq\BinanceManager\Spot\Spot\Repositories\Synchronizers;

use Empiriq\BinanceContracts\Spot\Spot\Events\Market\DepthEvent;
use React\Promise\PromiseInterface;

use function React\Promise\resolve;

/**
 * @internal
 */
trait DepthSynchronizer
{
    public function __synchronize(): PromiseInterface
    {
        $this->dispatcher->addListener(DepthEvent::class, [$this, 'onDepth'], PHP_INT_MAX);
        return resolve([]);
    }

    public function onDepth(DepthEvent $event): void
    {
        $this->add($event);
    }
}
