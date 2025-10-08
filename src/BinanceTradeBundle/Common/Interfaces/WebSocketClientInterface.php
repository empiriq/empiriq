<?php

namespace Empiriq\BinanceTradeBundle\Common\Interfaces;

use React\Promise\PromiseInterface;

interface WebSocketClientInterface
{
    /**
     * @return PromiseInterface<self>
     */
    public function connect(): PromiseInterface;

    /**
     * @return PromiseInterface<self>
     */
    public function disconnect(): PromiseInterface;
}
