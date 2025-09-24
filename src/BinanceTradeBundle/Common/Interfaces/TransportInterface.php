<?php

namespace Empiriq\BinanceTradeBundle\Common\Interfaces;

use React\Promise\PromiseInterface;

interface TransportInterface
{
    public function run(): PromiseInterface;

    public function shutdown(): void;

    public function isLoggedIn(): bool;
}
