<?php

namespace Empiriq\BinanceTradeBundle\Common\Interfaces\Streams;

use Empiriq\BinanceTradeBundle\Spot\Spot\SpotMarket;
use React\Promise\PromiseInterface;

interface SpotStreamInterface
{
    public function subscribe(SpotMarket $market): PromiseInterface;

    //todo public function gertSymbols(): array; // common contract for repository
}
