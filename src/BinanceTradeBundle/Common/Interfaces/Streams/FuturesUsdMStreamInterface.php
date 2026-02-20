<?php

namespace Empiriq\BinanceTradeBundle\Common\Interfaces\Streams;

use Empiriq\BinanceTradeBundle\Markets\FuturesUm\FuturesUm;
use React\Promise\PromiseInterface;

interface FuturesUsdMStreamInterface
{
    public function subscribe(FuturesUm $market): PromiseInterface;

    //todo public function gertSymbols(): array; // common contract for repository
}
