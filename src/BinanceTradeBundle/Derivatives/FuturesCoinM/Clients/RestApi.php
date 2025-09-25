<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients;

use Empiriq\BinanceTradeBundle\Common\Clients\Rest\RestClient;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;
use Empiriq\Contracts\SerializerInterface;
use Psr\Log\LoggerInterface;
use React\Http\Browser;

/**
 * @see https://developers.binance.com/docs/derivatives/coin-margined-futures/general-info
 */
final class RestApi extends RestClient
{
    /**
     * @param string $uri
     * @param string $apiKey
     * @param SignerInterface $signer
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     * @param SanitizerInterface $sanitizer
     * @param float $resolverTimeout
     */
    public function __construct(
        string $uri,
        protected string $apiKey,
        protected SignerInterface $signer,
        protected SerializerInterface $serializer,
        protected LoggerInterface $logger,
        protected SanitizerInterface $sanitizer,
        float $resolverTimeout = 10,
    ) {
        $this->client = (new Browser())
            ->withBase($uri)
            ->withTimeout($resolverTimeout)
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded');
    }
}
