<?php

namespace Empiriq\BinanceRealBundle\Common\Interfaces\Streams;

use Empiriq\BinanceRealBundle\Markets\Spot\Spot;
use React\Promise\PromiseInterface;

interface SpotStreamInterface
{
    public function subscribe(Spot $market): PromiseInterface;

    //todo public function gertSymbols(): array; // common contract for repository
}
