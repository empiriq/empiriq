<?php

namespace Empiriq\TickerBundle\Clock;

use Empiriq\Contracts\RunnableInterface;
use Empiriq\TickerBundle\DependencyInjection\Compiler\ClockBuildPass;
use Empiriq\TickerBundle\TickEvent;
use React\EventLoop\Loop;
use React\EventLoop\TimerInterface;
use React\Promise\PromiseInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use function React\Promise\resolve;

class TimeDrivenClock implements RunnableInterface
{
    /**
     * @var TimerInterface[]
     */
    private array $timers = [];

    /**
     * @param string[] $intervals
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        private readonly array $intervals,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    public function run(): PromiseInterface
    {
        foreach ($this->intervals as $interval) {
            $seconds = (float)$interval;
            $this->timers[] = Loop::addPeriodicTimer(
                $seconds,
                function () use ($interval, $seconds) {
                    $this->dispatcher->dispatch(
                        new TickEvent(new \DateTimeImmutable(), $seconds),
                        sprintf('%s?%s=%s', ClockBuildPass::EVENT_PATH, ClockBuildPass::QUERY_PARAM, $interval)
                    );
                }
            );
        }

        return resolve(null);
    }

    public function shutdown(): PromiseInterface
    {
        foreach ($this->timers as $timer) {
            Loop::cancelTimer($timer);
        }

        return resolve(null);
    }
}
