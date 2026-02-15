<?php

namespace Empiriq\Contracts;

use Empiriq\BinanceTradeBundle\Common\Exceptions\Configuration\ConfigurationException;
use Psr\Log\LoggerInterface;

readonly class Runner
{
    /**
     * @param iterable<RunnableInterface> $runners
     * @param LoggerInterface $logger
     */
    public function __construct(
        private iterable $runners,
        private LoggerInterface $logger,
    ) {
        foreach ($this->runners as $runner) {
            if (!$runner instanceof RunnableInterface) {
                throw new ConfigurationException('Invalid Runnable');
            }
        }
    }

    public function run(): void
    {
        $this->logger->info('Runtime starting');
        foreach ($this->runners as $runner) {
            $this->logger->info(sprintf('Running: %s', $runner::class));
            $runner->run();
        }
        $this->logger->info('Runtime started successfully');
        if (!\extension_loaded('pcntl')) {
            $this->logger->warning('pcntl extension not available, signal handling disabled');
            return;
        }
        pcntl_async_signals(true);
        pcntl_signal(SIGTERM, fn() => $this->shutdown(SIGTERM));
        pcntl_signal(SIGINT, fn() => $this->shutdown(SIGINT));
        $this->logger->info('Signal handlers registered');
    }

    private function shutdown(int $signal): void
    {
        $name = match ($signal) {
            SIGINT => 'SIGINT',
            SIGTERM => 'SIGTERM',
            default => (string)$signal,
        };

        $this->logger->info(sprintf('Received %s, initiating shutdown...', $name));

        foreach ($this->runners as $service) {
            try {
                $service->shutdown();
            } catch (\Throwable $e) {
                $this->logger->error(
                    sprintf(
                        'Shutdown error in %s: %s',
                        $service::class,
                        $e->getMessage()
                    )
                );
            }
        }

        $this->logger->info('Shutdown completed');
        exit(0);
    }
}
