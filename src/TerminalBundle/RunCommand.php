<?php

namespace Empiriq\TerminalBundle;

use Empiriq\Contracts\RunnableInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\SignalableCommandInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

use function React\Async\await;
use function React\Promise\all;

use const SIGINT;
use const SIGTERM;

/**
 * @api Console command to run and gracefully stop runnable services.
 */
#[AsCommand('run', 'Run all runnable services')]
final class RunCommand extends Command implements SignalableCommandInterface
{
    /**
     * @param iterable<RunnableInterface> $runners
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly iterable $runners,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct();
    }

    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->runByPriority($this->runners);
        } catch (Throwable $e) {
            $this->logger->error(sprintf('Runner execution failed: %s (%s)', $e->getMessage(), $e::class));
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    #[\Override]
    public function getSubscribedSignals(): array
    {
        return [
            SIGINT, // Ctrl+C
            SIGTERM, // kill
        ];
    }

    #[\Override]
    public function handleSignal(int $signal, int|false $previousExitCode = 0): int|false
    {
        $name = $signal === SIGINT ? 'SIGINT' : 'SIGTERM';
        $this->logger->info("Received {$name}, shutting down…");

        try {
            $this->shutdownByPriority($this->runners);
        } catch (Throwable $e) {
            $this->logger->error(sprintf('Shutdown error: %s', $e->getMessage()));
            return Command::FAILURE;
        }
        $this->logger->info('Shutdown complete');

        return $previousExitCode;
    }

    private function runByPriority(iterable $runners): void
    {
        foreach ($this->groupByPriority($runners, 'DESC') as $priority => $group) {
            $this->logger->info("Starting group priority {$priority}");
            $promises = [];
            foreach ($group as $runner) {
                $this->logger->info(sprintf('Running: %s', $runner::class));
                $promises[] = $runner->run();
            }
            await(all($promises));
        }
    }

    private function shutdownByPriority(iterable $runners): void
    {
        foreach ($this->groupByPriority($runners, 'ASC') as $priority => $group) {
            $this->logger->info("Stopping group priority {$priority}");
            $promises = [];
            foreach ($group as $runner) {
                $this->logger->info(sprintf('Shutdown: %s', $runner::class));
                $promises[] = $runner->shutdown();
            }
            await(all($promises));
        }
    }

    /**
     * @param iterable<RunnableInterface> $runners
     * @return array<int, RunnableInterface[]>
     */
    private function groupByPriority(iterable $runners, string $direction = 'ASC'): array
    {
        $groups = [];
        foreach ($runners as $runner) {
            $groups[$runner->getPriority()][] = $runner;
        }
        if ($direction === 'ASC') {
            ksort($groups);
        } else {
            krsort($groups);
        }

        return $groups;
    }
}
