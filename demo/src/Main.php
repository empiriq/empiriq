<?php

namespace App;

use Empiriq\BinanceContracts\Markets\FuturesUm\Requests\MarketData\Depth;
use Empiriq\BinanceRealBundle\Common\Messaging\FuturesUmWsApiCommandMessage;
use Empiriq\Contracts\Runner;
use React\Promise\PromiseInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

use function React\Async\await;

readonly class Main implements EventSubscriberInterface
{
    public function __construct(
        private MessageBusInterface $bus,
        private Runner $runner,
    ) {
    }

    #[\Override]
    public static function getSubscribedEvents(): array
    {
        return [
            'empiriq.run' => 'run',
        ];
    }

    public function run(): void
    {
        $message = new FuturesUmWsApiCommandMessage(
            new Depth(symbol: 'BTCUSDT', limit: 5)
        );
        $result = $this->bus->dispatch($message)->last(HandledStamp::class)?->getResult();
        if ($result instanceof PromiseInterface) {
            var_dump(await($result));
        }
        $this->runner->shutdown();
    }
}
