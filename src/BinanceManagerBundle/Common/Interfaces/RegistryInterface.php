<?php

namespace Empiriq\BinanceManagerBundle\Common\Interfaces;

use React\Promise\PromiseInterface;

interface RegistryInterface
{
    /**
     * @param RepositoryInterface[] $repositories
     */
    public function __construct(array $repositories);

    /**
     * @return PromiseInterface<self>
     */
    public function __synchronize(): PromiseInterface;

    public function findRepository(string $className): ?RepositoryInterface;

    public function getRepository(string $className): RepositoryInterface;
}
