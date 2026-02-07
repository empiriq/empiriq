<?php

namespace Empiriq\TickerBundle\Clock;

use Empiriq\Contracts\RunnableInterface;
use Empiriq\TickerBundle\TickEvent;
use React\EventLoop\Loop;
use React\EventLoop\TimerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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

    public function run(): void
    {
        foreach ($this->intervals as $interval) {
            $this->timers[] = Loop::addPeriodicTimer(
                $this->intervalToSeconds($interval),
                function () use ($interval) {
                    $this->dispatcher->dispatch(
                        new TickEvent(
                            period: $interval,
                            time: new \DateTimeImmutable()
                        ),
                        sprintf('ticker.tick.%s', $interval)
                    );
                }
            );
        }
    }

    public function shutdown(): void
    {
        foreach ($this->timers as $timer) {
            Loop::cancelTimer($timer);
        }
    }

    /**
     * Converts a time interval to seconds (float).
     *
     * Supported formats:
     *  - "1s", "1.5s"         → seconds
     *  - "250ms"              → milliseconds
     *  - "100us"              → microseconds
     *  - "2m"                 → minutes
     *  - "1h"                 → hours
     */
    private function intervalToSeconds(string $interval): float
    {
        if (!preg_match('/^\s*(\d+(?:\.\d+)?)\s*(us|ms|s|m|h)\s*$/i', $interval, $matches)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid interval format: "%s"', $interval)
            );
        }
        $value = (float)$matches[1];
        $unit = strtolower($matches[2]);
        if ($value < 0) {
            throw new \InvalidArgumentException('Interval must be non-negative');
        }

        return match ($unit) {
            'us' => $value / 1_000_000,
            'ms' => $value / 1_000,
            's' => $value,
            'm' => $value * 60,
            'h' => $value * 3600,
        };
    }
}
