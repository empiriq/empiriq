<?php

namespace Empiriq\BinanceRealBundle\Markets\Spot\Clients;

use Empiriq\BinanceContracts\Markets\Spot\Common\EventInterface;
use Empiriq\BinanceRealBundle\Common\Clients\WebSocket\ResponseResolver;
use Empiriq\BinanceRealBundle\Common\Configs\WebSocketConfig;
use Empiriq\BinanceRealBundle\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceRealBundle\Common\Interfaces\SignerInterface;
use Empiriq\Contracts\Messaging\EventPublisherInterface;
use Empiriq\Contracts\SerializerInterface;
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
 *
 * Constructed by the DI container.
 *
 * @api
 */
final class WsSubscriptions extends ResponseResolver
{
    /**
     * @param EventPublisherInterface $publisher Event publisher.
     * @param SignerInterface $signer Request signer.
     * @param SerializerInterface $serializer Payload serializer.
     * @param LoggerInterface $logger PSR-3 logger instance.
     * @param SanitizerInterface $sanitizer Sanitizer for sensitive data before logging.
     * @param WebSocketConfig $config WebSocket configuration.
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
        return isset($data['e']) ? $data : null;
    }

    #[\Override]
    protected static function getEventType(): string
    {
        return EventInterface::class;
    }
}
