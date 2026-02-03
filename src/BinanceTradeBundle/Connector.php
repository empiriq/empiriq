<?php

namespace Empiriq\BinanceTradeBundle;

use Empiriq\BinanceTradeBundle\Common\Exceptions\Configuration\ConfigurationException;
use Empiriq\BinanceTradeBundle\Common\Interfaces\TransportInterface;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\FuturesCoinMTransport;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\FuturesUsdMTransport;
use Empiriq\BinanceTradeBundle\Spot\Spot\SpotTransport;
use Empiriq\Contracts\ExchangeConnectorInterface;
use Empiriq\Contracts\RunnableInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Throwable;

use function React\Async\await;
use function React\Promise\all;

/**
 * @api
 */
readonly class Connector implements ExchangeConnectorInterface, RunnableInterface
{
    /**
     * @param TransportInterface[] $transports
     * @param LoggerInterface $logger
     */
    public function __construct(
        private array $transports,
        private LoggerInterface $logger = new NullLogger(),
    ) {
        foreach ($this->transports as $transport) {
            if (!$transport instanceof TransportInterface) {
                throw new ConfigurationException('Invalid Transport');
            }
        }
    }

    /**
     * @throws Throwable
     */
    #[\Override]
    public function run(): void
    {
        $this->logger->info('Starting connector run loop...');
        $connections = [];
        foreach ($this->transports as $transport) {
            $this->logger->info(sprintf('Connecting receiver: %s', get_class($transport)));
            $connections[] = $transport->run();
        }
        try {
            await(all($connections));
        } catch (Throwable $exception) {
            $this->logger->critical(
                sprintf('Error in connector (code: %s, message: %s)', $exception->getCode(), $exception->getMessage())
            );
        }
    }

    #[\Override]
    public function shutdown(): void
    {
        $this->logger->info('Shutting down connector...');
        $connections = [];
        foreach ($this->transports as $transport) {
            $connections[] = $transport->shutdown();
        }
        await(all($connections));
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
