<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesCoinM\Repositories\Synchronizers;

use React\Promise\PromiseInterface;

use function React\Promise\resolve;

/**
 * @internal
 */
trait DepthSynchronizer
{
    #[\Override]
    public function __synchronize(): PromiseInterface
    {
        return resolve($this);
    }
}
