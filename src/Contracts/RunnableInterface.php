<?php

namespace Empiriq\Contracts;

use React\Promise\PromiseInterface;

/**
 * Interface for any service that can be started (run) and stopped (shutdown).
 * Each service has a priority, which defines the order of execution.
 */
interface RunnableInterface
{
    /**
     * High priority for exchange connectors.
     * These should run before other services.
     */
    public const EXCHANGE_CONNECTOR_PRIORITY = 1000;

    /**
     * Lower priority for socket servers.
     * These should run after connectors.
     */
    public const SOCKET_SERVER_PRIORITY = 900;

    /**
     * Start the service.
     *
     * @return PromiseInterface Resolves when the service is running.
     */
    public function run(): PromiseInterface;

    /**
     * Stop the service.
     *
     * @return PromiseInterface Resolves when the service is fully stopped.
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
