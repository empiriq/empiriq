<?php

namespace Empiriq\BinanceTradeBundle\Common\Interfaces;

use React\Promise\PromiseInterface;

//todo rename WebSocketClientInterface
interface ClientInterface
{
    /**
     * @return PromiseInterface<self>
     */
    public function run(): PromiseInterface;

    /**
     * @return void
     */
    public function shutdown(): void; //todo return PromiseInterface
}
