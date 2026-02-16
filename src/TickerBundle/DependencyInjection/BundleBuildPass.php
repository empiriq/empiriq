<?php

namespace Empiriq\TickerBundle\DependencyInjection;

use Empiriq\SymfonyEventDiscovery\EventDiscovery;
use Empiriq\TickerBundle\Clock\EventDrivenClock;
use Empiriq\TickerBundle\Clock\TimeDrivenClock;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

readonly class BundleBuildPass implements CompilerPassInterface
{
    /**
     * Builds the clock service based on discovered tick subscriptions.
     */
    public function __construct(
        private EventDiscovery $eventDiscovery
    ) {
    }


    /**
     * Configures the active clock and its tick intervals.
     */
    public function process(ContainerBuilder $container): void
    {
        $clockType = $container->getParameter('ticker.clock_type');
        $eventNames = $this->eventDiscovery->discover($container);
        $intervals = [];
        foreach ($eventNames as $eventName) {
            $intervals = array_merge($intervals, $this->extractIntervals($eventName));
        }
        $intervals = array_values(array_unique($intervals));

        $container->setDefinition(
            'ticker.clock',
            new Definition(
                match ($clockType) {
                    'time' => TimeDrivenClock::class,
                    'event' => EventDrivenClock::class,
                    default => throw new \InvalidArgumentException(
                        sprintf('Invalid tick clock type "%s". Allowed values: time, event.', $clockType)
                    ),
                },
                [
                    $intervals,
                    new Reference('event_dispatcher')
                ]
            )
        )
            ->addTag('empiriq.runnable');
    }

    /**
     * Extracts tick intervals from a subscription name.
     *
     * @return string[]
     */
    private function extractIntervals(string $eventName): array
    {
        if (str_starts_with($eventName, 'ticker.tick?')) {
            return $this->extractIntervalsFromQuery($eventName);
        }

        if (preg_match('/^ticker\.tick\.(.+)$/i', $eventName, $matches)) {
            return $this->filterValidIntervals($this->splitList($matches[1]));
        }

        return [];
    }

    /**
     * Extracts intervals from query-style subscriptions.
     *
     * @return string[]
     */
    private function extractIntervalsFromQuery(string $eventName): array
    {
        $parts = explode('?', $eventName, 2);
        if (count($parts) !== 2 || $parts[0] !== 'ticker.tick') {
            return [];
        }

        parse_str($parts[1], $query);
        $intervals = [];

        if (isset($query['interval']) && is_string($query['interval'])) {
            $intervals = array_merge($intervals, $this->splitList($query['interval']));
        }

        return $this->filterValidIntervals($intervals);
    }

    /**
     * Filters and normalizes interval values.
     *
     * @param string[] $values
     * @return string[]
     */
    private function filterValidIntervals(array $values): array
    {
        $valid = [];
        foreach ($values as $value) {
            $value = trim($value);
            if ($value === '') {
                continue;
            }
            if (preg_match('/^\d+(?:\.\d+)?(?:us|ms|s|m|h)$/i', $value) === 1) {
                $valid[] = $value;
            }
        }

        return $valid;
    }

    /**
     * Splits a comma-separated interval list.
     *
     * @return string[]
     */
    private function splitList(string $value): array
    {
        return array_map('trim', explode(',', $value));
    }
}
