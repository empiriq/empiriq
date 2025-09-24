<?php

namespace Empiriq\Contracts;

use React\Promise\PromiseInterface;

interface ConnectorInterface
{
    /**
     * Run the connector
     *
     * @return PromiseInterface
     */
    public function run(): PromiseInterface;

    /**
     * @return void
     */
    public function shutdown(): void;
}
