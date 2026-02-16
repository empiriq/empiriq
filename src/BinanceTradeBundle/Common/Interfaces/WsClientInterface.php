<?php

namespace Empiriq\BinanceTradeBundle\Common\Interfaces;

use React\Promise\PromiseInterface;

interface WsClientInterface
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
