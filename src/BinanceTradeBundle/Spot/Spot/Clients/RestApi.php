<?php

namespace Empiriq\BinanceTradeBundle\Spot\Spot\Clients;

use Empiriq\BinanceTradeBundle\Common\Clients\Rest\RestClient;
use Empiriq\BinanceTradeBundle\Common\Helpers\Serializer;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;
use Empiriq\BinanceTradeBundle\Common\Signers\NullSigner;
use Empiriq\Contracts\SerializerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use React\Http\Browser;

/**
 * @see https://developers.binance.com/docs/binance-spot-api-docs/testnet/rest-api/general-api-information
 * @see https://developers.binance.com/docs/binance-spot-api-docs/rest-api/general-api-information
 */
final class RestApi extends RestClient
{
    /**
     * @param string $uri testnet https://testnet.binance.vision/api
     * @param string $apiKey
     * @param SignerInterface $signer
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     * @param Browser $client
     * @param float $resolverTimeout
     */
    public function __construct(
        protected string $uri = 'https://api.binance.com',
        protected string $apiKey = '',
        protected SignerInterface $signer = new NullSigner(),
        protected SerializerInterface $serializer = new Serializer(),
        protected LoggerInterface $logger = new NullLogger(),
        protected Browser $client = new Browser(),
        protected float $resolverTimeout = 5,
    ) {
    }
}
