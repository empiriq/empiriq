<?php

namespace Empiriq\BinanceTradeBundle\Spot\Spot\Clients;

use Empiriq\BinanceContracts\Spot\Spot\Common\EventInterface;
use Empiriq\BinanceTradeBundle\Common\Clients\WebSocket\ResponseResolver;
use Empiriq\BinanceTradeBundle\Common\Configs\WebSocketConfig;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\WebSocketClientInterface;
use Empiriq\Contracts\SerializerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

/**
 * Handles WebSocket connections to Binance Spot market streams.
 *
 * API endpoints:
 *  - Production:
 *  - Testnet:
 *
 * Documentation:
 *    - Production: {@link https://developers.binance.com/docs/binance-spot-api-docs/web-socket-streams}
 *    - Testnet: {@link https://developers.binance.com/docs/binance-spot-api-docs/testnet/web-socket-streams}
 */
final class WebSocketStreams extends ResponseResolver implements WebSocketClientInterface
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
