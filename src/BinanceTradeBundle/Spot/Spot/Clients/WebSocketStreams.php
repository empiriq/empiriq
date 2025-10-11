<?php

namespace Empiriq\BinanceTradeBundle\Spot\Spot\Clients;

use Empiriq\BinanceTradeBundle\Common\Clients\WebSocket\ResponseResolver;
use Empiriq\BinanceTradeBundle\Common\Helpers\Sanitizer;
use Empiriq\BinanceTradeBundle\Common\Helpers\Serializer;
use Empiriq\BinanceTradeBundle\Common\Interfaces\WebSocketClientInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceContracts\Spot\Spot\Common\EventInterface;
use Empiriq\Contracts\SerializerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Handles WebSocket connections to Binance Spot market streams.
 *
 * Aggregates multiple SpotStreamInterface implementations into a single WebSocket connection,
 * deserializes incoming messages into SpotEvent objects, and dispatch via EventDispatcher.
 *
 * @see https://developers.binance.com/docs/binance-spot-api-docs/testnet/web-socket-streams
 * @see https://developers.binance.com/docs/binance-spot-api-docs/web-socket-streams
 */
final class WebSocketStreams extends ResponseResolver implements WebSocketClientInterface
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
        protected string $uri = '', // todo set main net
        protected SerializerInterface $serializer = new Serializer(),
        protected LoggerInterface $logger = new NullLogger(),
        protected SanitizerInterface $sanitizer = new Sanitizer(),
        protected float $resolverTimeout = 5,
    ) {
    }

    #[\Override]
    protected static function extractRawResponse(array $data): ?array
    {
        return isset($data['id']) ? $data : null;
    }

    #[\Override]
    protected static function extractRawEvent(array $data): ?array
    {
        return isset($data['e']) ? $data : null;
    }

    #[\Override]
    protected static function getEventType(): string
    {
        return EventInterface::class;
    }
}
