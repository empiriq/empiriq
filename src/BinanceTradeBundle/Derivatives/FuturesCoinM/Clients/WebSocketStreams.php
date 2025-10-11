<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients;

use Empiriq\BinanceTradeBundle\Common\Clients\WebSocket\ResponseResolver;
use Empiriq\BinanceTradeBundle\Common\Helpers\Sanitizer;
use Empiriq\BinanceTradeBundle\Common\Helpers\Serializer;
use Empiriq\BinanceTradeBundle\Common\Interfaces\WebSocketClientInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\EventInterface;
use Empiriq\Contracts\SerializerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Handles WebSocket connections to Binance USD Margined Futures market streams.
 *
 * Aggregates multiple FuturesCmStreamInterface implementations into a single web socket connection,
 * deserializes incoming messages into FuturesCmEvent objects, and dispatch via EventDispatcher.
 *
 * @see https://developers.binance.com/docs/derivatives/coin-margined-futures/websocket-market-streams
 */
final class WebSocketStreams extends ResponseResolver implements WebSocketClientInterface
{
    /**
     * @param EventDispatcherInterface $dispatcher
     * @param string $uri testnet wss://dstream.binancefuture.com/ws
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     * @param SanitizerInterface $sanitizer
     * @param float $resolverTimeout
     */
    public function __construct(
        protected EventDispatcherInterface $dispatcher,
        protected string $uri = 'wss://dstream.binance.com/ws',
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
