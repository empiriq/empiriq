<?php

namespace Empiriq\BinanceTradeBundle\Spot\Spot\Clients;

use Empiriq\BinanceTradeBundle\Common\Clients\Rest\RestClient;
use Empiriq\BinanceTradeBundle\Common\Configs\RestApiConfig;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;
use Empiriq\Contracts\SerializerInterface;
use Psr\Log\LoggerInterface;
use React\Http\Browser;

/**
 * REST API client for Spot.
 *
 * API endpoints:
 * - Production: https://api.binance.com
 * - Test: https://testnet.binance.vision/api
 *
 * The actual base URI is provided via {@see RestApiConfig}.
 *
 * Documentation:
 *  - Production: {@link https://developers.binance.com/docs/binance-spot-api-docs/rest-api/general-api-information}
 *  - Test: {@link https://developers.binance.com/docs/binance-spot-api-docs/testnet/rest-api/general-api-information}
 */
final class RestApi extends RestClient
{
    /**
     * @param SignerInterface $signer Request signer.
     * @param SerializerInterface $serializer Payload serializer.
     * @param LoggerInterface $logger PSR-3 logger instance.
     * @param Browser $client ReactPHP HTTP client.
     * @param RestApiConfig $config REST API configuration.
     */
    public function __construct(
        protected SignerInterface $signer,
        protected SerializerInterface $serializer,
        protected LoggerInterface $logger,
        protected Browser $client,
        protected RestApiConfig $config,
    ) {
    }
}
