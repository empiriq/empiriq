<?php

namespace App\Runtime;

use Empiriq\Contracts\RunnableInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

#[Autoconfigure(public: true)]
final class SignalManager
{
    private bool $shutdownRequested = false;

    /**
     * @param iterable<RunnableInterface> $stoppables
     */
    public function __construct(
        #[AutowireIterator('empiriq.runnable')]
        private readonly iterable $stoppables,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function register(): void
    {
        if (!\extension_loaded('pcntl')) {
            $this->logger->warning('pcntl extension not available, signal handling disabled');
            return;
        }

        pcntl_async_signals(true);

        pcntl_signal(SIGTERM, fn () => $this->shutdown(SIGTERM));
        pcntl_signal(SIGINT, fn () => $this->shutdown(SIGINT));

        $this->logger->info('Signal handlers registered');
    }

    private function shutdown(int $signal): void
    {
        if ($this->shutdownRequested) {
            return;
        }

        $this->shutdownRequested = true;

        $name = match ($signal) {
            SIGINT => 'SIGINT',
            SIGTERM => 'SIGTERM',
            default => (string) $signal,
        };

        $this->logger->info(sprintf('Received %s, initiating shutdown...', $name));

        foreach ($this->stoppables as $service) {
            try {
                $service->shutdown();
            } catch (\Throwable $e) {
                $this->logger->error(sprintf(
                    'Shutdown error in %s: %s',
                    $service::class,
                    $e->getMessage()
                ));
            }
        }

        $this->logger->info('Shutdown completed');
        exit(0);
    }
}
