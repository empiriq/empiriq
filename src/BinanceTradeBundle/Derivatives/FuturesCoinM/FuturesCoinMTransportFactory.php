<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM;

use Empiriq\BinanceTradeBundle\Common\Helpers\Sanitizer;
use Empiriq\BinanceTradeBundle\Common\Helpers\Serializer;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\Streams\FuturesCoinMStreamInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\TransportInterface;
use Empiriq\BinanceTradeBundle\Common\Signers\NullSigner;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients\RestApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients\WebsocketApi;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients\WebsocketStreams;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class FuturesCoinMTransportFactory
{
    /**
     * @param EventDispatcherInterface $dispatcher
     * @param string $apiKey
     * @param SignerInterface $signer
     * @param string $restApiUri
     * @param string $websocketApiUri
     * @param string $websocketStreamsUri
     * @param DecoderInterface&SerializerInterface&NormalizerInterface&EncoderInterface&DenormalizerInterface $serializer
     * @param SanitizerInterface $sanitizer
     * @param float $resolverTimeout
     * @param LoggerInterface $logger
     */
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private string $apiKey = '',
        private SignerInterface $signer = new NullSigner(),
        private string $restApiUri = 'https://dapi.binance.com', // testnet https://testnet.binancefuture.com
        private string $websocketApiUri = 'wss://ws-dapi.binance.com/ws-dapi/v1', // testnet wss://testnet.binancefuture.com/ws-dapi/v1
        private string $websocketStreamsUri = 'wss://dstream.binance.com/ws', // testnet wss://dstream.binancefuture.com/ws
        private SerializerInterface & NormalizerInterface & DenormalizerInterface & EncoderInterface & DecoderInterface $serializer = new Serializer(),
        private SanitizerInterface $sanitizer = new Sanitizer(),
        private float $resolverTimeout = 10,
        private LoggerInterface $logger = new Logger('FUTURES_COIN_M', [
            new StreamHandler('php://stderr', Level::Debug),
        ]),
    ) {
    }

    /**
     * @param FuturesCoinMStreamInterface[] $streams
     * @return TransportInterface
     */
    public function createTransport(array $streams): TransportInterface
    {
        return new FuturesCoinMTransport(
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
