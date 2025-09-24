<?php

namespace Empiriq\BinanceManager;

use BadMethodCallException;
use Empiriq\BinanceManager\Common\Interfaces\RegistryInterface;
use Empiriq\Contracts\ConnectorInterface;
use Empiriq\Contracts\ManagerInterface;
use LogicException;
use React\Promise\PromiseInterface;

readonly class Manager implements ManagerInterface
{
    /**
     * @param ConnectorInterface $connector
     * @param array<string, RegistryInterface> $registries
     */
    public function __construct(
        public ConnectorInterface $connector,
        public array $registries,
    ) {
        foreach ($this->registries as $registry) {
            if (!$registry instanceof RegistryInterface) {
                throw new LogicException();
            }
        }
    }

    /**
     * @return PromiseInterface
     */
    public function run(): PromiseInterface
    {
        return $this->connector->run()
            ->then(fn() => array_map(
                fn(RegistryInterface $registry) => $registry->__synchronize(),
                $this->registries
            ));
    }

    /**
     * @template T of RegistryInterface
     *
     * @param class-string<T> $className
     * @return T|null
     */
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
