<?php

namespace Empiriq\Server;

use Empiriq\Contracts\EnvironmentInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ServerCommand extends Command
{
    /**
     * @param LoggerInterface $logger
     * @param iterable<EnvironmentInterface> $environments
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly iterable $environments,
    ) {
        parent::__construct(ENTRYPOINT); //todo fix
    }

    protected function configure(): void
    {
        $this
            ->setHelp(
                <<<'HELP'
                The <info>%command.name%</info> command lists all the users registered in the application:

                  <info>php %command.full_name%</info>
                HELP
            )
            ->addArgument(
                'environment',
                InputArgument::REQUIRED,
                'The mode (например: backtrade, papertrade, realtrade)',
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $environment = $input->getArgument('environment');
        $this->logger->error('asdda');
        $this->logger->debug(sprintf('Running in environment: %s', $environment));
        foreach ($this->environments as $environment) {
            $environment->run();
        }

        return Command::SUCCESS;
    }
}
