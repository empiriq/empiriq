<?php

namespace App\Runtime;

use Empiriq\Contracts\RunnableInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

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
        foreach ($this->runners as $runner) {
            $this->logger->info(sprintf('Running: %s', $runner::class));
            $runner->run();
        }
        $this->logger->info('Runtime started successfully');
    }
}
