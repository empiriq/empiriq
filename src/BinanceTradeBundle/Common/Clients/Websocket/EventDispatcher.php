<?php

namespace Empiriq\BinanceTradeBundle\Common\Clients\Websocket;

use Psr\EventDispatcher\EventDispatcherInterface;
use Throwable;

abstract class EventDispatcher extends Connection
{
    protected EventDispatcherInterface $dispatcher;

    #[\Override]
    protected function message(array $data): void
    {
        if ($rawEvent = static::extractRawEvent($data)) {
            try {
                $event = $this->serializer->denormalize($rawEvent, static::getEventType());
                $this->dispatcher->dispatch($event);
            } catch (Throwable $exception) {
                $this->logger->warning(sprintf('Event denormalization failed: %s', $exception->getMessage()), $data);
            }
        }
    }

    abstract protected static function extractRawEvent(array $data): ?array;

    abstract protected static function getEventType(): string;
}
