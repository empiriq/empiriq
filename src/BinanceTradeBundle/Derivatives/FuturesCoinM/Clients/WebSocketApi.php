<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients;

use Empiriq\BinanceTradeBundle\Common\Clients\WebSocket\ResponseResolver;
use Empiriq\BinanceTradeBundle\Common\Helpers\Sanitizer;
use Empiriq\BinanceTradeBundle\Common\Helpers\Serializer;
use Empiriq\BinanceTradeBundle\Common\Interfaces\WebSocketClientInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;
use Empiriq\BinanceContracts\Derivatives\FuturesCoinM\Common\EventInterface;
use Empiriq\BinanceTradeBundle\Common\Signers\NullSigner;
use Empiriq\Contracts\SerializerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @see https://developers.binance.com/docs/derivatives/coin-margined-futures/websocket-api-general-info
 */
final class WebSocketApi extends ResponseResolver implements WebSocketClientInterface
{
    /**
     * @param EventDispatcherInterface $dispatcher
     * @param string $uri testnet wss://testnet.binancefuture.com/ws-dapi/v1
     * @param string $apiKey
     * @param SignerInterface $signer
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     * @param SanitizerInterface $sanitizer
     * @param float $resolverTimeout
     */
    public function __construct(
        protected EventDispatcherInterface $dispatcher,
        protected string $uri = 'wss://ws-dapi.binance.com/ws-dapi/v1',
        protected string $apiKey = '',
        protected SignerInterface $signer = new NullSigner(),
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
        return $data['event'] ?? null;
    }

    #[\Override]
    protected static function getEventType(): string
    {
        return EventInterface::class;
    }
}
