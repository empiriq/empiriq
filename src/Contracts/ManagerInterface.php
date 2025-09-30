<?php

namespace Empiriq\Contracts;

/**
 * @api
 */
interface ManagerInterface
{
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
