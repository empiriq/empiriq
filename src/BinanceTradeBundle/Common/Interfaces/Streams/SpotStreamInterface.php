<?php

namespace Empiriq\BinanceTradeBundle\Common\Interfaces\Streams;

use Empiriq\BinanceTradeBundle\Spot\Spot\SpotTransport;
use React\Promise\PromiseInterface;

interface SpotStreamInterface
{
    public function subscribe(SpotTransport $transport): PromiseInterface;

    //todo public function gertSymbols(): array; // common contract for repository
}
