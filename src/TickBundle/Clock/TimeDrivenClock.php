<?php

namespace Empiriq\TickBundle\Clock;

use Empiriq\Contracts\RunnableInterface;
use Empiriq\TickBundle\DependencyInjection\Compiler\ClockBuildPass;
use Empiriq\TickBundle\TickEvent;
use React\EventLoop\Loop;
use React\EventLoop\TimerInterface;
use React\Promise\PromiseInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use function React\Promise\resolve;

/**
 * Time-driven ticker clock constructed by the DI container.
 *
 * @api
 */
final class TimeDrivenClock implements RunnableInterface
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

    #[\Override]
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

    #[\Override]
    public function shutdown(): PromiseInterface
    {
        foreach ($this->timers as $timer) {
            Loop::cancelTimer($timer);
        }

        return resolve(null);
    }
}
