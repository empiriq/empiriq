<?php

namespace Empiriq\BinanceTradeBundle\Spot\Spot;

use Empiriq\BinanceTradeBundle\Common\Helpers\Sanitizer;
use Empiriq\BinanceTradeBundle\Common\Helpers\Serializer;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\SpotStreamInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\TransportInterface;
use Empiriq\BinanceTradeBundle\Common\Signers\NullSigner;
use Empiriq\BinanceTradeBundle\Spot\Spot\Clients\RestApi;
use Empiriq\BinanceTradeBundle\Spot\Spot\Clients\WebsocketApi;
use Empiriq\BinanceTradeBundle\Spot\Spot\Clients\WebsocketStreams;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class SpotTransportFactory
{
    /**
     * @param EventDispatcherInterface $dispatcher
     * @param string $apiKey
     * @param SignerInterface $signer
     * @param string $restApiUri
     * @param string $websocketApiUri
     * @param string $websocketStreamsUri
     * @param SerializerInterface $serializer
     * @param SanitizerInterface $sanitizer
     * @param float $resolverTimeout
     * @param LoggerInterface $logger
     */
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private string $apiKey = '',
        private SignerInterface $signer = new NullSigner(),
        private string $restApiUri = 'https://api.binance.com', // testnet https://testnet.binance.vision/api
        private string $websocketApiUri = 'wss://ws-api.testnet.binance.vision/ws-api/v3', // testnet wss://ws-api.testnet.binance.vision/ws-api/v3
        private string $websocketStreamsUri = 'wss://stream.binance.com:9443/ws', // testnet ???
        private SerializerInterface $serializer = new Serializer(),
        private SanitizerInterface $sanitizer = new Sanitizer(),
        private float $resolverTimeout = 10,
        private LoggerInterface $logger = new Logger('SPOT', [
            new StreamHandler('php://stderr', Level::Debug),
        ]),
    ) {
    }

    /**
     * @param SpotStreamInterface[] $streams
     * @return TransportInterface
     */
    public function createTransport(array $streams): TransportInterface
    {
        return new SpotTransport(
            $streams,
            new RestApi(
                $this->restApiUri,
                $this->apiKey,
                $this->signer,
                $this->serializer,
                $this->logger,
                $this->sanitizer,
                $this->resolverTimeout,
            ),
            new WebsocketApi(
                $this->dispatcher,
                $this->websocketApiUri,
                $this->apiKey,
                $this->signer,
                $this->serializer,
                $this->logger,
                $this->sanitizer,
                $this->resolverTimeout,
            ),
            new WebsocketStreams(
                $this->dispatcher,
                $this->websocketStreamsUri,
                $this->serializer,
                $this->logger,
                $this->sanitizer,
                $this->resolverTimeout,
            )
        );
    }
}
