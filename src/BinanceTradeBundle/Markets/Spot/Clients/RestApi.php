<?php

namespace Empiriq\BinanceTradeBundle\Markets\Spot\Clients;

use Empiriq\BinanceTradeBundle\Common\Clients\Rest\RestClient;
use Empiriq\BinanceTradeBundle\Common\Configs\RestConfig;
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
 * The actual base URI is provided via {@see RestConfig}.
 *
 * Documentation:
 *  - Production: {@link https://developers.binance.com/docs/binance-spot-api-docs/rest-api/general-api-information}
 *  - Test: {@link https://developers.binance.com/docs/binance-spot-api-docs/testnet/rest-api/general-api-information}
 *
 * Constructed by the DI container.
 *
 * @api
 */
final class RestApi extends RestClient
{
    /**
     * @param SignerInterface $signer Request signer.
     * @param SerializerInterface $serializer Payload serializer.
     * @param LoggerInterface $logger PSR-3 logger instance.
     * @param Browser $client ReactPHP HTTP client.
     * @param RestConfig $config REST API configuration.
     */
    public function __construct(
        SignerInterface $signer,
        SerializerInterface $serializer,
        LoggerInterface $logger,
        Browser $client,
        RestConfig $config,
    ) {
        parent::__construct($signer, $serializer, $logger, $client, $config);
    }
}
