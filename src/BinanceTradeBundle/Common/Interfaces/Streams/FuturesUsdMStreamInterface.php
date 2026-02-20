<?php

namespace Empiriq\BinanceTradeBundle\Common\Interfaces\Streams;

use Empiriq\BinanceTradeBundle\Markets\FuturesUsdM\FuturesUsdM;
use React\Promise\PromiseInterface;

interface FuturesUsdMStreamInterface
{
    public function subscribe(FuturesUsdM $market): PromiseInterface;

    //todo public function gertSymbols(): array; // common contract for repository
}
