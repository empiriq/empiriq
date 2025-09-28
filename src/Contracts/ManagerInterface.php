<?php

namespace Empiriq\Contracts;

use React\Promise\PromiseInterface;

/**
 * @api
 */
interface ManagerInterface
{
    /**
     * @api
     * @return PromiseInterface
     */
    public function run(): PromiseInterface;

    /**
     * @api
     * @template T
     *
     * @param class-string<T> $className
     * @return T|null
     */
    public function findRegistry(string $className): mixed;

    /**
     * @api
     * @template T
     *
     * @param class-string<T> $className
     * @return T
     */
    public function getRegistry(string $className): mixed;
}
