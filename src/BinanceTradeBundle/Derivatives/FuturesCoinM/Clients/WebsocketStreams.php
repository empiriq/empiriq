<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients;

use Empiriq\BinanceTradeBundle\Common\Clients\Websocket\ResponseResolver;
use Empiriq\BinanceTradeBundle\Common\Interfaces\ClientInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\EventInterface;
use Empiriq\Contracts\SerializerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

/**
 * Handles WebSocket connections to Binance USD Margined Futures market streams.
 *
 * Aggregates multiple FuturesCmStreamInterface implementations into a single web socket connection,
 * deserializes incoming messages into FuturesCmEvent objects, and dispatch via EventDispatcher.
 *
 * @see https://developers.binance.com/docs/derivatives/coin-margined-futures/websocket-market-streams
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
