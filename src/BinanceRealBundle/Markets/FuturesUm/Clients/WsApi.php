<?php

namespace Empiriq\BinanceRealBundle\Markets\FuturesUm\Clients;

use Empiriq\BinanceContracts\Markets\FuturesUm\Common\EventInterface;
use Empiriq\BinanceRealBundle\Common\Clients\WebSocket\ResponseResolver;
use Empiriq\BinanceRealBundle\Common\Configs\WebSocketConfig;
use Empiriq\BinanceRealBundle\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceRealBundle\Common\Interfaces\SignerInterface;
use Empiriq\Contracts\Messaging\EventPublisherInterface;
use Empiriq\Contracts\SerializerInterface;
use Psr\Log\LoggerInterface;

/**
 * WebSocket API client for USD-M Futures.
 *
 * API endpoints:
 * - Production: wss://fstream.binance.com/ws
 * - Testnet: wss://testnet.binancefuture.com/ws-fapi/v1
 *
 * The actual WebSocket URI is configurable via {@see WebSocketConfig}.
 *
 * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/websocket-api-general-info
 *
 * Constructed by the DI container.
 *
 * @api
 */
final class WsApi extends ResponseResolver
{
    /**
     * @param EventPublisherInterface $publisher Event publisher.
     * @param SignerInterface $signer Request signer.
     * @param SerializerInterface $serializer Payload serializer.
     * @param LoggerInterface $logger PSR-3 logger instance.
     * @param SanitizerInterface $sanitizer Sanitizer for sensitive data before logging.
     * @param WebSocketConfig $config WebSocket API configuration.
     */
    public function __construct(
        protected EventPublisherInterface $publisher,
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
