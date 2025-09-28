<?php

namespace Empiriq\Demo;

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
        $this->logger->info(sprintf('gracefully shutdown'));

        return Command::SUCCESS;
    }
}
