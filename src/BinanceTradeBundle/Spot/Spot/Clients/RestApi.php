<?php

namespace Empiriq\BinanceTradeBundle\Spot\Spot\Clients;

use Empiriq\BinanceTradeBundle\Common\Clients\Rest\RestClient;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SanitizerInterface;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;
use Psr\Log\LoggerInterface;
use React\Http\Browser;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @see https://developers.binance.com/docs/binance-spot-api-docs/testnet/rest-api/general-api-information
 * @see https://developers.binance.com/docs/binance-spot-api-docs/rest-api/general-api-information
 */
final class RestApi extends RestClient
{
    /**
     * @param string $uri
     * @param string $apiKey
     * @param SignerInterface $signer
     * @param NormalizerInterface & DenormalizerInterface & EncoderInterface & DecoderInterface $serializer
     * @param LoggerInterface $logger
     * @param SanitizerInterface $sanitizer
     * @param float $resolverTimeout
     */
    public function __construct(
        string $uri,
        protected string $apiKey,
        protected SignerInterface $signer,
        protected NormalizerInterface & DenormalizerInterface & EncoderInterface & DecoderInterface $serializer,
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
