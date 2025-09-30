<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories\Synchronizers;

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
        return resolve($this);
    }
}
