<?php

namespace Empiriq\TickerBundle\DependencyInjection\Compiler;

use Empiriq\SymfonyEventDiscovery\EventDiscovery;
use Empiriq\TickerBundle\Clock\EventDrivenClock;
use Empiriq\TickerBundle\Clock\TimeDrivenClock;
use Empiriq\TickerBundle\DependencyInjection\TickerExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\HeaderUtils;

readonly class ClockBuildPass implements CompilerPassInterface
{
    public const EVENT_PATH = 'tick';
    public const QUERY_PARAM = 'interval';

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
        $configs = $container->getParameter(TickerExtension::PARAMETER_NAME);
        $eventNames = $this->eventDiscovery->discover($container);
        $intervals = [];
        foreach ($eventNames as $eventName) {
            $interval = $this->extractInterval($eventName);
            if ($interval !== null) {
                $intervals[] = $interval;
            }
        }
        $intervals = array_values(array_unique($intervals));

        $container->setDefinition(
            'ticker.clock',
            new Definition(
                match ($configs['clock']) {
                    'time' => TimeDrivenClock::class,
                    'event' => EventDrivenClock::class,
                    default => throw new \InvalidArgumentException(
                        sprintf('Invalid tick clock type "%s". Allowed values: time, event.', $configs['clock'])
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
     * Extracts the interval value from a ticker event name.
     */
    private function extractInterval(string $eventName): ?string
    {
        if (parse_url($eventName, PHP_URL_PATH) !== self::EVENT_PATH) {
            return null;
        }
        $query = parse_url($eventName, PHP_URL_QUERY);
        if (!is_string($query) || $query === '') {
            return null;
        }
        $params = HeaderUtils::parseQuery($query);
        $interval = $params[self::QUERY_PARAM] ?? null;
        if (!is_string($interval) || $interval === '') {
            return null;
        }
        if (!is_numeric($interval) || (float)$interval <= 0.0) {
            return null;
        }

        return $interval;
    }
}
