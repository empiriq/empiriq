<?php

namespace Empiriq\Contracts;

use React\Promise\PromiseInterface;

/**
 * Interface for any service that can be started (run) and stopped (shutdown).
 */
interface RunnableInterface
{
    /**
     * @return PromiseInterface<null>
     */
    public function run(): PromiseInterface;

    /**
     * @return PromiseInterface<null>
     */
    public function shutdown(): PromiseInterface;
}
