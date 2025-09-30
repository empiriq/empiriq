<?php

namespace Empiriq\BinanceManagerBundle\Derivatives\FuturesUsdM\Repositories\Synchronizers;

use React\Promise\PromiseInterface;

use function React\Promise\resolve;

/**
 * @internal
 */
trait TransactionHistorySynchronizer
{
    #[\Override]
    public function __synchronize(): PromiseInterface
    {
        return resolve($this);
    }
}
