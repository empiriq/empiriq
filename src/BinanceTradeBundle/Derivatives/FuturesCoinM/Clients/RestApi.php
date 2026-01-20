<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients;

use Empiriq\BinanceTradeBundle\Common\Clients\Rest\RestClient;
use Empiriq\BinanceTradeBundle\Common\Configs\RestApiConfig;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;
use Empiriq\Contracts\SerializerInterface;
use Psr\Log\LoggerInterface;
use React\Http\Browser;

/**
 * REST API client for COIN-M Futures.
 *
 * API endpoints:
 * - Production: https://dapi.binance.com
 * - Testnet: https://testnet.binancefuture.com
 *
 * The actual base URI is provided via {@see RestApiConfig}.
 *
 * @see https://developers.binance.com/docs/derivatives/coin-margined-futures/general-info
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
