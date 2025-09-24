<?php

namespace Empiriq\Contracts;

use React\Promise\PromiseInterface;

interface ManagerInterface
{
    /**
     * @return PromiseInterface
     */
    public function run(): PromiseInterface;

    /**
     * @template T
     *
     * @param class-string<T> $className
     * @return T|null
     */
    public function findRegistry(string $className): mixed;

    /**
     * @template T
     *
     * @param class-string<T> $className
     * @return T
     */
    public function getRegistry(string $className): mixed;
}
