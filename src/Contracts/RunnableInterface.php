<?php

namespace Empiriq\Contracts;

/**
 * Interface for any service that can be started (run) and stopped (shutdown).
 */
interface RunnableInterface
{
    public function run(): void;

    public function shutdown(): void;
}
