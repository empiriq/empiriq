<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients;

use Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Common\EventInterface;
use Empiriq\BinanceTradeBundle\Common\Clients\WebSocket\ResponseResolver;
use Empiriq\BinanceTradeBundle\Common\Configs\WebSocketConfig;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SanitizerInterface;
use Empiriq\Contracts\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * API endpoints:
 *  - Production: wss://fstream.binance.com/ws
 *  - Testnet: wss://fstream.binancefuture.com/ws
 *
 * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/websocket-market-streams
 */
final class WsSubscriptions extends ResponseResolver
{
    /**
     * @param EventDispatcherInterface $dispatcher Event dispatcher.
     * @param SerializerInterface $serializer Payload serializer.
     * @param LoggerInterface $logger PSR-3 logger instance.
     * @param SanitizerInterface $sanitizer Sanitizer for sensitive data before logging.
     * @param WebSocketConfig $config WebSocket configuration.
     */
    public function __construct(
        protected EventDispatcherInterface $dispatcher,
        protected SerializerInterface $serializer,
        protected LoggerInterface $logger,
        protected SanitizerInterface $sanitizer,
        protected WebSocketConfig $config,
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
