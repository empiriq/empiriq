<?php

namespace Empiriq\BinanceBackTradeBundle;

use Empiriq\BinanceBackTradeBundle\Common\Helpers\ParallelIterator;
use Empiriq\BinanceBackTradeBundle\Common\Interfaces\ReceiverInterface;
use Empiriq\Contracts\ExchangeConnectorInterface;
use Empiriq\Contracts\RunnableInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;

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
    public function run(): void
    {
        // todo resolve immediately
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
    }

    #[\Override]
    public function shutdown(): void
    {
    }
}
