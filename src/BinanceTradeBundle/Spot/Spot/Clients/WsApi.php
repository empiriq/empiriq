<?php

namespace Empiriq\BinanceTradeBundle\Spot\Spot\Clients;

use Empiriq\BinanceContracts\Spot\Spot\Common\EventInterface;
use Empiriq\BinanceTradeBundle\Common\Clients\WebSocket\ResponseResolver;
use Empiriq\BinanceTradeBundle\Common\Configs\WebSocketConfig;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\WsClientInterface;
use Empiriq\Contracts\SerializerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

/**
 * WebSocket API client for Spot.
 *
 * API endpoints:
 * - Production:
 * - Testnet: wss://ws-api.testnet.binance.vision/ws-api/v3
 *
 * The actual WebSocket URI is configurable via {@see WebSocketConfig}.
 *
 * Documentation:
 *   - Production:
 * {@link https://developers.binance.com/docs/binance-spot-api-docs/websocket-api/general-api-information}
 *   - Testnet:
 * {@link https://developers.binance.com/docs/binance-spot-api-docs/testnet/websocket-api/general-api-information}
 */
final class WsApi extends ResponseResolver implements WsClientInterface
{
    /**
     * @param EventDispatcherInterface $dispatcher Event dispatcher.
     * @param SignerInterface $signer Request signer.
     * @param SerializerInterface $serializer Payload serializer.
     * @param LoggerInterface $logger PSR-3 logger instance.
     * @param SanitizerInterface $sanitizer Sanitizer for sensitive data before logging.
     * @param WebSocketConfig $config WebSocket API configuration.
     */
    public function __construct(
        protected EventDispatcherInterface $dispatcher,
        protected SignerInterface $signer,
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
        return $data['event'] ?? null;
    }

    #[\Override]
    protected static function getEventType(): string
    {
        return EventInterface::class;
    }
}
