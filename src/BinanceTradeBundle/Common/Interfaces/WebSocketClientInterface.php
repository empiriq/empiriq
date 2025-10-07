<?php

namespace Empiriq\BinanceTradeBundle\Common\Interfaces;

use React\Promise\PromiseInterface;

interface WebSocketClientInterface
{
    /**
     * @return PromiseInterface<self>
     */
    public function initialize(): PromiseInterface;

    /**
     * @return PromiseInterface<self>
     */
    public function deinitialize(): PromiseInterface;
}
