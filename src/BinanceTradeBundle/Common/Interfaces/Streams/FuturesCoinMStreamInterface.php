<?php

namespace Empiriq\BinanceTradeBundle\Common\Interfaces\Streams;

use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\FuturesCoinMTransport;
use React\Promise\PromiseInterface;

interface FuturesCoinMStreamInterface
{
    public function subscribe(FuturesCoinMTransport $transport): PromiseInterface;

    //todo public function gertSymbols(): array; // common contract for repository
}
