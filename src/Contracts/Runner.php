<?php

namespace Empiriq\Contracts;

use Empiriq\BinanceTradeBundle\Common\Exceptions\Configuration\ConfigurationException;
use Empiriq\Contracts\Events\EmpiriqRunEvent;
use Empiriq\Contracts\Events\EmpiriqShutdownEvent;
use Psr\Log\LoggerInterface;
use React\Promise\PromiseInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use function React\Async\await;
use function React\Promise\all;

readonly class Runner
{
    /**
     * @var RunnableInterface[]
     */
    private array $runners;

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface $logger
     * @param iterable<RunnableInterface> $runners
     */
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private LoggerInterface $logger,
        iterable $runners,
    ) {
        $this->runners = $this->normalizeRunners($runners);
    }

    public function run(): void
    {
        $this->logger->info('Runtime starting');
        $promises = [];
        foreach ($this->runners as $runner) {
            $this->logger->info(sprintf('Running: %s', $runner::class));
            $promises[] = $this->handlePromise($runner->run(), $runner::class, 'run');
        }
        await(all($promises));
        $this->dispatcher->dispatch(new EmpiriqRunEvent(), 'empiriq.run');
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

        $promises = [];
        foreach ($this->runners as $service) {
            $promises[] = $this->handlePromise($service->shutdown(), $service::class, 'shutdown');
        }

        await(all($promises));
        $this->dispatcher->dispatch(new EmpiriqShutdownEvent(), 'empiriq.shutdown');
        $this->logger->info('Shutdown completed');
        exit(0);
    }

    private function handlePromise(PromiseInterface $promise, string $service, string $operation): PromiseInterface
    {
        return $promise->then(
            null,
            function (\Throwable $e) use ($service, $operation) {
                $this->logger->error(sprintf('%s error in %s: %s', ucfirst($operation), $service, $e->getMessage()));
                return null;
            }
        );
    }

    /**
     * @param iterable<RunnableInterface> $runners
     * @return RunnableInterface[]
     */
    private function normalizeRunners(iterable $runners): array
    {
        $normalized = [];
        foreach ($runners as $runner) {
            if (!$runner instanceof RunnableInterface) {
                throw new ConfigurationException('Invalid Runnable');
            }
            $normalized[] = $runner;
        }

        return $normalized;
    }
}
