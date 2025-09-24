<?php

namespace Empiriq\BinanceTradeBundle;

use Empiriq\BinanceTradeBundle\Common\Exceptions\Configuration\ConfigurationException;
use Empiriq\BinanceTradeBundle\Common\Interfaces\TransportInterface;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\FuturesCoinMTransport;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\FuturesUsdMTransport;
use Empiriq\BinanceTradeBundle\Spot\Spot\SpotTransport;
use Empiriq\Contracts\ConnectorInterface;
use Empiriq\Contracts\EnvironmentInterface;
use Psr\Log\LoggerInterface;
use React\Promise\PromiseInterface;
use Throwable;

use function React\Promise\all;

readonly class Connector implements ConnectorInterface, EnvironmentInterface
{
    /**
     * @param TransportInterface[] $transports
     * @param LoggerInterface $logger
     */
    public function __construct(
        private array $transports,
        private LoggerInterface $logger,
    ) {
        foreach ($this->transports as $transport) {
            if (!$transport instanceof TransportInterface) {
                throw new ConfigurationException('Invalid Transport');
            }
        }
    }

    /**
     * @return PromiseInterface
     * @throws Throwable
     */
    public function run(): PromiseInterface
    {
        var_dump(2);
        $this->logger->info('Starting connector run loop...');
        $connections = [];
        foreach ($this->transports as $transport) {
            $this->logger->info(sprintf('Connecting receiver: %s', get_class($transport)));
            $connections[] = $transport->run();
        }

        return all($connections)->catch(function (Throwable $exception) {
            $this->logger->critical(
                sprintf('Error in connector (code: %s, message: %s)', $exception->getCode(), $exception->getMessage())
            );
            throw $exception;
        })->then(function () {
            return $this;
        });
    }

    /**
     * @return void
     */
    public function shutdown(): void
    {
        $this->logger->info('Shutting down connector...');
        foreach ($this->transports as $transport) {
            $this->logger->info(sprintf('Disconnecting receiver: %s', get_class($transport)));
            $transport->shutdown();
        }
        $this->logger->info('Connector stopped successfully');
    }

    /**
     * @template T of TransportInterface
     *
     * @param class-string<T> $className
     * @return T
     */
    private function getTransport(string $className): TransportInterface
    {
        $transport = array_find(
            $this->transports,
            fn(TransportInterface $transport): bool => get_class($transport) === $className
        );
        if (!$transport) {
            throw new ConfigurationException(sprintf('Transport not found: : "%s".', $className));
        }

        return $transport;
    }

    /**
     * @return FuturesCoinMTransport
     */
    public function futuresCoinM(): FuturesCoinMTransport
    {
        return $this->getTransport(FuturesCoinMTransport::class);
    }

    /**
     * @return FuturesUsdMTransport
     */
    public function futuresUsdM(): FuturesUsdMTransport
    {
        return $this->getTransport(FuturesUsdMTransport::class);
    }

    /**
     * @return SpotTransport
     */
    public function spot(): SpotTransport
    {
        return $this->getTransport(SpotTransport::class);
    }
}
