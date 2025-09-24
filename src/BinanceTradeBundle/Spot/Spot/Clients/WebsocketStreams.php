<?php

namespace Empiriq\BinanceTradeBundle\Spot\Spot\Clients;

use Empiriq\BinanceTradeBundle\Common\Clients\Websocket\ResponseResolver;
use Empiriq\BinanceTradeBundle\Common\Interfaces\ClientInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceContracts\Spot\Spot\Common\EventInterface;
use Empiriq\Contracts\SerializerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

/**
 * Handles WebSocket connections to Binance Spot market streams.
 *
 * Aggregates multiple SpotStreamInterface implementations into a single WebSocket connection,
 * deserializes incoming messages into SpotEvent objects, and dispatch via EventDispatcher.
 *
 * @see https://developers.binance.com/docs/binance-spot-api-docs/testnet/web-socket-streams
 * @see https://developers.binance.com/docs/binance-spot-api-docs/web-socket-streams
 */
final class WebsocketStreams extends ResponseResolver implements ClientInterface
{
    /**
     * @param EventDispatcherInterface $dispatcher
     * @param string $uri
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     * @param SanitizerInterface $sanitizer
     * @param float $resolverTimeout
     */
    public function __construct(
        protected EventDispatcherInterface $dispatcher,
        protected string $uri,
        protected SerializerInterface $serializer,
        protected LoggerInterface $logger,
        protected SanitizerInterface $sanitizer,
        protected float $resolverTimeout,
    ) {
    }

    protected static function extractRawResponse(array $data): ?array
    {
        return isset($data['id']) ? $data : null;
    }

    protected static function extractRawEvent(array $data): ?array
    {
        return isset($data['e']) ? $data : null;
    }

    protected static function getEventType(): string
    {
        return EventInterface::class;
    }
}
