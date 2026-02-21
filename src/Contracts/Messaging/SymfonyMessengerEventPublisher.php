<?php

namespace Empiriq\Contracts\Messaging;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;

/**
 * Messenger-based event publisher.
 *
 * @api
 */
final readonly class SymfonyMessengerEventPublisher implements EventPublisherInterface
{
    /**
     * @param string[] $selectorSubscriptions
     */
    public function __construct(
        private MessageBusInterface $bus,
        private ?EventSelectorResolverInterface $selectorResolver = null,
        private array $selectorSubscriptions = [],
    ) {
    }

    #[\Override]
    public function publish(object $event, ?string $eventName = null): void
    {
        if (is_string($eventName) && $eventName !== '') {
            $this->bus->dispatch(new DomainEventMessage($event, $eventName));
            return;
        }

        if ($this->selectorResolver) {
            $resolved = $this->selectorResolver->resolve($event);
            if (is_array($resolved)) {
                $matched = $this->matchSubscriptions(
                    $resolved['path'],
                    $resolved['selectors'],
                    $this->selectorSubscriptions
                );
                if ($matched !== []) {
                    foreach ($matched as $name) {
                        $this->bus->dispatch(new DomainEventMessage($event, $name));
                    }
                    return;
                }

                $this->bus->dispatch(new DomainEventMessage($event, $resolved['path']));
                return;
            }
        }

        $this->bus->dispatch(new DomainEventMessage($event));
    }

    /**
     * @param array<string, string> $selectors
     * @param string[] $subscriptions
     * @return string[]
     */
    private function matchSubscriptions(string $path, array $selectors, array $subscriptions): array
    {
        $matches = [];
        foreach ($subscriptions as $subscription) {
            if ($subscription === '') {
                continue;
            }
            $subscriptionPath = parse_url($subscription, PHP_URL_PATH);
            if (!is_string($subscriptionPath) || $subscriptionPath !== $path) {
                continue;
            }

            $subscriptionQuery = parse_url($subscription, PHP_URL_QUERY);
            if (!is_string($subscriptionQuery) || $subscriptionQuery === '') {
                $matches[] = $subscription;
                continue;
            }

            $query = HeaderUtils::parseQuery($subscriptionQuery);
            if ($this->matchesSelectors($query, $selectors)) {
                $matches[] = $subscription;
            }
        }

        return $matches;
    }

    /**
     * @param array<string, mixed> $query
     * @param array<string, string> $selectors
     */
    private function matchesSelectors(array $query, array $selectors): bool
    {
        foreach ($query as $name => $value) {
            $actual = $selectors[$name] ?? null;
            if (!is_string($actual) || $actual === '') {
                return false;
            }
            $actual = strtolower($actual);

            $expectedValues = $this->normalizeExpectedValues($value);
            if ($expectedValues === []) {
                continue;
            }
            if (!in_array($actual, $expectedValues, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return string[]
     */
    private function normalizeExpectedValues(mixed $value): array
    {
        $values = is_array($value) ? $value : [$value];
        $normalized = [];
        foreach ($values as $item) {
            if (!is_scalar($item)) {
                continue;
            }
            foreach (explode(',', strtolower((string)$item)) as $part) {
                $part = trim($part);
                if ($part !== '') {
                    $normalized[] = $part;
                }
            }
        }

        return array_values(array_unique($normalized));
    }
}
