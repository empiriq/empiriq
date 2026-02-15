<?php

namespace Empiriq\BinanceTradeBundle\Common\Interfaces\Streams;

use Empiriq\BinanceTradeBundle\FuturesUsdMTransport;
use React\Promise\PromiseInterface;

interface FuturesUsdMStreamInterface
{
    public function subscribe(FuturesUsdMTransport $transport): PromiseInterface;

    //todo public function gertSymbols(): array; // common contract for repository
}
