<?php

namespace Empiriq\Demo;

use Empiriq\Contracts\RunnableInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function React\Async\await;
use function React\Promise\all;

#[AsCommand('run', 'Run all runnable services')]
final class RunCommand extends Command
{
    /**
     * @param LoggerInterface $logger
     * @param iterable<RunnableInterface> $runners
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly iterable $runners,
    ) {
        parent::__construct();
    }

    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $runners = [];
        foreach ($this->runners as $runner) {
            $this->logger->info(sprintf('Running: %s', $runner::class));
            $runners[] = $runner->run();
        }
        try {
            await(all($runners));
        } catch (\Throwable $e) {
            $this->logger->error(sprintf('Runner execution failed: %s (%s)', $e->getMessage(), $e::class));
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    public function shutdown(): void
    {
        $runners = [];
        foreach ($this->runners as $runner) {
            $this->logger->info(sprintf('Running: %s', $runner::class));
            $runners[] = $runner->shutdown();
        }
        try {
            await(all($runners));
        } catch (\Throwable $e) {
            $this->logger->error(sprintf('shutdown'));
        }
    }
}
