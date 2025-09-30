<?php

namespace Empiriq\BinanceManagerBundle\Spot\Spot\Repositories\Synchronizers;

use React\Promise\PromiseInterface;

use function React\Promise\resolve;

/**
 * @internal
 */
trait TradeHistorySynchronizer
{
    #[\Override]
    public function __synchronize(): PromiseInterface
    {
        return resolve($this);
    }
}
