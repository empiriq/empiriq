<?php

namespace Empiriq\Demo\Command;

use Empiriq\BinanceManagerBundle\BinanceManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand('ls', 'Lists all trades')]
final class ListCommand extends Command
{
    public function __construct(
        #[Autowire('@empiriq.binance.manager')]
        private readonly BinanceManager $manager,
    ) {
        parent::__construct();
    }

    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('yeee! futures-usd:trade:list');
        var_dump($this->manager->futuresUsdM()->balance()->findAll());

        return Command::SUCCESS;
    }
}
