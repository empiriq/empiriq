<?php

namespace Empiriq\BinanceTradeBundle\Common\Interfaces;

use React\Promise\PromiseInterface;

interface TransportInterface
{
    public function run(): PromiseInterface;

    public function shutdown(): PromiseInterface;

    public function isLoggedIn(): bool;
}
