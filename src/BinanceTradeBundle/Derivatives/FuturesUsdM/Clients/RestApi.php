<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\Clients;

use Empiriq\BinanceTradeBundle\Common\Clients\Rest\RestClient;
use Empiriq\BinanceTradeBundle\Common\Helpers\Serializer;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;
use Empiriq\BinanceTradeBundle\Common\Signers\NullSigner;
use Empiriq\Contracts\SerializerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use React\Http\Browser;

/**
 * @see https://developers.binance.com/docs/derivatives/usds-margined-futures/general-info
 */
final class RestApi extends RestClient
{
    /**
     * @param string $uri testnet https://testnet.binancefuture.com
     * @param string $apiKey
     * @param SignerInterface $signer
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     * @param Browser $client
     * @param float $resolverTimeout
     */
    public function __construct(
        protected string $uri = 'https://fapi.binance.com',
        protected string $apiKey = '',
        protected SignerInterface $signer = new NullSigner(),
        protected SerializerInterface $serializer = new Serializer(),
        protected LoggerInterface $logger = new NullLogger(),
        protected Browser $client = new Browser(),
        protected float $resolverTimeout = 5,
    ) {
    }
}
