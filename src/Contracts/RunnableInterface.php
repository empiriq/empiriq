<?php

namespace Empiriq\Contracts;

use React\Promise\PromiseInterface;

/**
 * Interface for any service that can be started (run) and stopped (shutdown).
 * Each service has a priority, which defines the order of execution.
 */
interface RunnableInterface
{
    public const EXCHANGE_CONNECTOR_PRIORITY = 1000;

    public const REPOSITORY_PRIORITY = 900;

    public const SOCKET_SERVER_PRIORITY = 500;

    /**
     * @return PromiseInterface<self> Resolves when the service is running.
     */
    public function run(): PromiseInterface;

    /**
     * @return PromiseInterface<self> Resolves when the service is fully stopped.
     */
    public function shutdown(): PromiseInterface;

    /**
     * Get the service priority.
     * Higher number = higher priority.
     *
     * Services are started in order from high to low priority.
     * Services are stopped in reverse order (low to high priority).
     *
     * @return int Priority value
     */
    public function getPriority(): int;
}
