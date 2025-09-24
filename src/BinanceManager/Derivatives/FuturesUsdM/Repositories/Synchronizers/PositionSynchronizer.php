<?php

namespace Empiriq\BinanceManager\Derivatives\FuturesUsdM\Repositories\Synchronizers;

use React\Promise\PromiseInterface;

use function React\Promise\resolve;

/**
 * @internal
 */
trait PositionSynchronizer
{
    public function __synchronize(): PromiseInterface
    {
        return resolve([]);
    }
}
