<?php

namespace Empiriq\BinanceBackTradeBundle;

use Empiriq\BinanceBackTradeBundle\Common\Helpers\ParallelIterator;
use Empiriq\BinanceBackTradeBundle\Common\Interfaces\ReceiverInterface;
use Empiriq\Contracts\ExchangeConnectorInterface;
use Empiriq\Contracts\RunnableInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use React\Promise\PromiseInterface;
use Symfony\Component\Serializer\SerializerInterface;

use function React\Promise\resolve;

class Connector implements ExchangeConnectorInterface, RunnableInterface
{
    /**
     * @param SerializerInterface $serializer
     * @param EventDispatcherInterface $dispatcher
     * @param ReceiverInterface[] $markets
     */
    public function __construct(
        protected SerializerInterface $serializer,
        protected EventDispatcherInterface $dispatcher,
        protected array $markets = [],
    ) {
        $this->start = microtime(true);
    }

    private float $start;

    public function addMarket(ReceiverInterface $market): RunnableInterface
    {
        $this->markets[] = $market;

        return $this;
    }

    #[\Override]
    public function run(): PromiseInterface
    {
        $eventIterator = new ParallelIterator(
            array_map(fn(ReceiverInterface $market) => $market->run($this->serializer), $this->markets)
        );
        $i = 0;
        foreach ($eventIterator as $event) {
            if (!($i % 1000)) {
                $end = microtime(true);
                $executionTime = round($end - $this->start);
                echo "Время выполнения: {$executionTime} секунд, счетчик: {$i}\n";
            }
            $i++;
            $this->dispatcher->dispatch($event);
        }

        return resolve($this);
    }

    #[\Override]
    public function shutdown(): PromiseInterface
    {
        return resolve($this);
    }

    #[\Override]
    public function getPriority(): int
    {
        return RunnableInterface::EXCHANGE_CONNECTOR_PRIORITY;
    }
}
