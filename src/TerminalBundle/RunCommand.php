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

    /**
     * Runs all services and waits for their completion.
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
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
        } catch (Throwable $e) {
            $this->logger->error(sprintf('Runner execution failed: %s (%s)', $e->getMessage(), $e::class));
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Returns handled signals.
     * @return array
     */
    #[\Override]
    public function getSubscribedSignals(): array
    {
        return [
            SIGINT, // Ctrl+C
            SIGTERM, // kill
        ];
    }

    /**
     * Handles shutdown when receiving system signals.
     * @param int $signal
     * @param int|false $previousExitCode
     * @return int|false
     */
    #[\Override]
    public function handleSignal(int $signal, int|false $previousExitCode = 0): int|false
    {
        $name = $signal === SIGINT ? 'SIGINT' : 'SIGTERM';
        $this->logger->info("Received {$name}, shutting down…");

        $runners = [];
        foreach ($this->runners as $runner) {
            $this->logger->info(sprintf('Shutdown: %s', $runner::class));
            $runners[] = $runner->shutdown();
        }
        try {
            await(all($runners));
        } catch (Throwable $e) {
            $this->logger->error(sprintf('Shutdown error: %s', $e->getMessage()));

            return Command::FAILURE;
        }
        $this->logger->info('gracefully shutdown');

        return Command::SUCCESS;
    }
}
