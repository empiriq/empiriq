<?php

namespace App\EventSubscriber;

use Empiriq\Contracts\RunnableInterface;
use Prometheus\CollectorRegistry;
use PrometheusPushGateway\PushGateway;
use React\EventLoop\Loop;
use React\EventLoop\TimerInterface;

final class MetricPusher implements RunnableInterface
{
    private ?TimerInterface $timer = null;

    public function __construct(
        private CollectorRegistry $registry,
        private PushGateway $gateway
    ) {
    }

    public function run(): void
    {
        $this->timer = Loop::addPeriodicTimer(10.0, [$this, '__push']);
    }

    public function __push(): void
    {
        $this->gateway->push($this->registry, 'trade_workers');
    }

    public function shutdown(): void
    {
        if ($this->timer !== null) {
            Loop::cancelTimer($this->timer);
            $this->timer = null;
        }
        $this->__push();
    }
}
