<?php

namespace Empiriq\BinanceTradeBundle\Common\Interfaces\Streams;

use Empiriq\BinanceTradeBundle\Markets\FuturesCoinM\FuturesCoinMMarket;
use React\Promise\PromiseInterface;

interface FuturesCoinMStreamInterface
{
    public function subscribe(FuturesCoinMMarket $market): PromiseInterface;

    //todo public function gertSymbols(): array; // common contract for repository
}
