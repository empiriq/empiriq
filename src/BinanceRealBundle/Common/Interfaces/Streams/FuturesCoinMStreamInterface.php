<?php

namespace Empiriq\BinanceRealBundle\Common\Interfaces\Streams;

use Empiriq\BinanceRealBundle\Markets\FuturesCm\FuturesCm;
use React\Promise\PromiseInterface;

interface FuturesCoinMStreamInterface
{
    public function subscribe(FuturesCm $market): PromiseInterface;

    //todo public function gertSymbols(): array; // common contract for repository
}
