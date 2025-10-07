<?php

namespace Empiriq\BinanceTradeBundle\Spot\Spot\Clients;

use Empiriq\BinanceTradeBundle\Common\Clients\WebSocket\ResponseResolver;
use Empiriq\BinanceTradeBundle\Common\Interfaces\WebSocketClientInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;
use Empiriq\BinanceContracts\Spot\Spot\Common\EventInterface;
use Empiriq\Contracts\SerializerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

/**
 * @see https://developers.binance.com/docs/binance-spot-api-docs/testnet/websocket-api/general-api-information
 * @see https://developers.binance.com/docs/binance-spot-api-docs/websocket-api/general-api-information
 */
final class WebSocketApi extends ResponseResolver implements WebSocketClientInterface
{
    /**
     * @param EventDispatcherInterface $dispatcher
     * @param string $uri
     * @param string $apiKey
     * @param SignerInterface $signer
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     * @param SanitizerInterface $sanitizer
     * @param float $resolverTimeout
     */
    public function __construct(
        protected EventDispatcherInterface $dispatcher,
        protected string $uri,
        protected string $apiKey,
        protected SignerInterface $signer,
        protected SerializerInterface $serializer,
        protected LoggerInterface $logger,
        protected SanitizerInterface $sanitizer,
        protected float $resolverTimeout,
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
