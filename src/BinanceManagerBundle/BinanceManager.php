<?php

namespace Empiriq\BinanceManagerBundle;

use BadMethodCallException;
use Empiriq\BinanceManagerBundle\Common\Interfaces\RegistryInterface;
use Empiriq\Contracts\ManagerInterface;
use Empiriq\Contracts\RunnableInterface;
use LogicException;
use React\Promise\PromiseInterface;

use function React\Promise\all;

readonly class BinanceManager implements ManagerInterface, RunnableInterface
{
    /**
     * @param RegistryInterface[] $registries
     */
    public function __construct(
        public array $registries,
    ) {
        foreach ($this->registries as $registry) {
            if (!$registry instanceof RegistryInterface) {
                throw new LogicException();
            }
        }
    }

    #[\Override]
    public function run(): void
    {
        all(
            array_map(
                static fn(RegistryInterface $registry): PromiseInterface => $registry->__synchronize(),
                $this->registries
            )
        );
    }

    #[\Override]
    public function shutdown(): void
    {
    }

    /**
     * @template T of RegistryInterface
     *
     * @param class-string<T> $className
     * @return T|null
     */
    #[\Override]
    public function findRegistry(string $className): ?RegistryInterface
    {
        return array_find(
            $this->registries,
            fn(RegistryInterface $registry): bool => get_class($registry) === $className
        );
    }

    /**
     * @template T of RegistryInterface
     *
     * @param class-string<T> $className
     * @return T
     */
    #[\Override]
    public function getRegistry(string $className): RegistryInterface
    {
        if (!($registry = $this->findRegistry($className))) {
            throw new BadMethodCallException('registry not found');
        }

        return $registry;
    }

    /**
     * @return Derivatives\FuturesCoinM\Registry
     */
    public function futuresCoinM(): Derivatives\FuturesCoinM\Registry
    {
        return $this->getRegistry(Derivatives\FuturesCoinM\Registry::class);
    }

    /**
     * @return Derivatives\FuturesUsdM\Registry
     */
    public function futuresUsdM(): Derivatives\FuturesUsdM\Registry
    {
        return $this->getRegistry(Derivatives\FuturesUsdM\Registry::class);
    }

    /**
     * @return Spot\Spot\Registry
     */
    public function spot(): Spot\Spot\Registry
    {
        return $this->getRegistry(Spot\Spot\Registry::class);
    }
}
