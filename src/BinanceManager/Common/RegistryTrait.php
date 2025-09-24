<?php

namespace Empiriq\BinanceManager\Common;

use Empiriq\BinanceManager\Common\Interfaces\RepositoryInterface;
use React\Promise\PromiseInterface;

use function React\Promise\all;

trait RegistryTrait
{
    /**
     * @return PromiseInterface<self>
     */
    public function __synchronize(): PromiseInterface
    {
        return all(
            array_map(
                fn(RepositoryInterface $repository) => $repository->__synchronize(),
                $this->repositories
            )
        );
    }

    /**
     * @template T of RepositoryInterface
     *
     * @param class-string<T> $className
     * @return T|null
     */
    public function findRepository(string $className): ?RepositoryInterface
    {
        return array_find(
            $this->repositories,
            fn(object $repository): bool => get_class($repository) === $className
        );
    }

    /**
     * @template T of RepositoryInterface
     *
     * @param class-string<T> $className
     * @return T
     */
    public function getRepository(string $className): RepositoryInterface
    {
        if (!($repository = $this->findRepository($className))) {
            throw new \BadMethodCallException('Repository not found');
        }

        return $repository;
    }
}
