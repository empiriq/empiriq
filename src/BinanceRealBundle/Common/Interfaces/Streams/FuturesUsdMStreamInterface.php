<?php

namespace Empiriq\BinanceRealBundle\Common\Interfaces\Streams;

use Empiriq\BinanceRealBundle\Markets\FuturesUm\FuturesUm;
use React\Promise\PromiseInterface;

interface FuturesUsdMStreamInterface
{
    public function subscribe(FuturesUm $market): PromiseInterface;

    //todo public function gertSymbols(): array; // common contract for repository
}
