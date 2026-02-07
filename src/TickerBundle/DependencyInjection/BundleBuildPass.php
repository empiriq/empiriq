<?php

namespace Empiriq\TickerBundle\DependencyInjection;

use Empiriq\SymfonyEventCollector\Collector;
use Empiriq\TickerBundle\Clock\EventDrivenClock;
use Empiriq\TickerBundle\Clock\TimeDrivenClock;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

readonly class BundleBuildPass implements CompilerPassInterface
{
    public function __construct(
        private Collector $collector
    ) {
    }


    public function process(ContainerBuilder $container): void
    {
        $clockType = $container->getParameter('ticker.clock_type');
        $eventNames = array_keys($this->collector->collect($container));
        $intervals = [];
        foreach ($eventNames as $eventName) {
            if (preg_match('/^ticker\.tick\.(\d+(?:\.\d+)?(?:us|ms|s|m|h))$/i', $eventName, $matches)) {
                $intervals[] = $matches[1];
            }
        }

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
}
