<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM;

use Empiriq\BinanceTradeBundle\Common\Helpers\Sanitizer;
use Empiriq\BinanceTradeBundle\Common\Helpers\Serializer;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\FuturesUsdMStreamInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\TransportInterface;
use Empiriq\BinanceTradeBundle\Common\Signers\NullSigner;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\RestApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WebsocketApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients\WebsocketStreams;
use Empiriq\Contracts\SerializerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

readonly class FuturesUsdMTransportFactory
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
        private string $restApiUri = 'https://fapi.binance.com', // testnet https://testnet.binancefuture.com
        private string $websocketApiUri = 'wss://ws-fapi.binance.com/ws-fapi/v1',
        // testnet wss://testnet.binancefuture.com/ws-fapi/v1
        private string $websocketStreamsUri = 'wss://fstream.binance.com/ws',
        // testnet wss://fstream.binancefuture.com/ws
        private SerializerInterface $serializer = new Serializer(),
        private SanitizerInterface $sanitizer = new Sanitizer(),
        private float $resolverTimeout = 10,
        private LoggerInterface $logger = new Logger('FUTURES_USD_M', [
            new StreamHandler('php://stderr', Level::Debug),
        ]),
    ) {
    }

    /**
     * @param FuturesUsdMStreamInterface[] $streams
     * @return TransportInterface
     */
    public function createTransport(array $streams): TransportInterface
    {
        return new FuturesUsdMTransport(
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
