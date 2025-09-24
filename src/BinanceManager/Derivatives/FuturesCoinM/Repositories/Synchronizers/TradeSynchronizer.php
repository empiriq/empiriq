<?php

namespace Empiriq\BinanceManager\Derivatives\FuturesCoinM\Repositories\Synchronizers;

use React\Promise\PromiseInterface;

use function React\Promise\resolve;

/**
 * @internal
 */
trait TradeSynchronizer
{
    public function __synchronize(): PromiseInterface
    {
        return resolve([]);
    }
}
