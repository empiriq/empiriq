<?php

namespace Empiriq\BinanceTradeBundle\Common\Interfaces\Streams;

use Empiriq\BinanceTradeBundle\Markets\Spot\Spot;
use React\Promise\PromiseInterface;

interface SpotStreamInterface
{
    public function subscribe(Spot $market): PromiseInterface;

    //todo public function gertSymbols(): array; // common contract for repository
}
