<?php

namespace Empiriq\BinanceTradeBundle\Common\Clients\WebSocket;

use Empiriq\Contracts\Messaging\EventPublisherInterface;
use Throwable;

abstract class EventDispatcher extends Connection
{
    protected EventPublisherInterface $publisher;

    #[\Override]
    protected function message(array $data): void
    {
        $rawEvent = static::extractRawEvent($data);
        if ($rawEvent !== null) {
            try {
                $event = $this->serializer->denormalize($rawEvent, static::getEventType());
                $this->publisher->publish($event);
            } catch (Throwable $exception) {
                $this->logger->warning(sprintf('Event denormalization failed: %s', $exception->getMessage()), $data);
            }
        }
    }

    abstract protected static function extractRawEvent(array $data): ?array;

    abstract protected static function getEventType(): string;
}
