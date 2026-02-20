<?php

namespace App;

use Empiriq\BinanceContracts\Markets\FuturesUm\FuturesUmInterface;
use Empiriq\Contracts\Runner;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use function React\Async\await;

readonly class Main implements EventSubscriberInterface
{
    public function __construct(
        private FuturesUmInterface $market,
        private Runner $runner,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'empiriq.run' => 'run',
        ];
    }

    public function run(): void
    {
        var_dump(await($this->market->ping()));
        $this->runner->shutdown();
    }
}
