<?php

namespace Empiriq\BinanceManager\Derivatives\FuturesUsdM\Repositories\Synchronizers;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Requests\MarketData\Depth;
use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\MarketData\DepthResponse;
use React\Promise\PromiseInterface;

/**
 * @internal
 */
trait DepthSynchronizer
{
    public function __synchronize(): PromiseInterface
    {
        return $this->sender->depth(new Depth('BTCUSDT', 5))->then(function (DepthResponse $data) {
            $this->result = $data->result;
        });
    }
}
