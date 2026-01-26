<?php

namespace App\Runtime;

use Empiriq\Contracts\RunnableInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Throwable;

use function React\Async\await;
use function React\Promise\all;

#[Autoconfigure(public: true)]
final readonly class RuntimeManager
{
    /**
     * @param iterable<RunnableInterface> $runners
     */
    public function __construct(
        #[AutowireIterator('empiriq.runnable')]
        private iterable $runners,
        private LoggerInterface $logger,
    ) {
    }

    public function run(): void
    {
        $this->logger->info('Runtime starting');

        $started = [];

        try {
            foreach ($this->groupByPriority($this->runners, true) as $priority => $group) {
                $this->logger->info(sprintf('Starting group priority %s', $priority));

                $promises = [];
                foreach ($group as $runner) {
                    $this->logger->info(sprintf('Running: %s', $runner::class));

                    $promises[] = $runner->run()->then(
                        function () use ($runner, &$started) {
                            $started[] = $runner;
                        }
                    );
                }

                await(all($promises));
            }
        } catch (Throwable $e) {
            $this->logger->error(sprintf(
                'Runtime startup failed: %s (%s)',
                $e->getMessage(),
                $e::class
            ));

            if ($started) {
                $this->logger->info('Rolling back started services');
                $this->shutdownByPriority($started);
            }

            throw $e;
        }

        $this->logger->info('Runtime started successfully');
    }

    private function shutdownByPriority(iterable $runners): void
    {
        foreach ($this->groupByPriority($runners) as $priority => $group) {
            $this->logger->info(sprintf('Stopping group priority %s', $priority));

            $promises = [];
            foreach ($group as $runner) {
                $this->logger->info(sprintf('Shutdown: %s', $runner::class));
                $promises[] = $runner->shutdown();
            }

            await(all($promises));
        }
    }

    /**
     * @return array<int, RunnableInterface[]>
     */
    private function groupByPriority(iterable $runners, bool $reverse = false): array
    {
        $groups = [];

        foreach ($runners as $runner) {
            $groups[$runner->getPriority()][] = $runner;
        }

        $reverse ? krsort($groups) : ksort($groups);

        return $groups;
    }
}
