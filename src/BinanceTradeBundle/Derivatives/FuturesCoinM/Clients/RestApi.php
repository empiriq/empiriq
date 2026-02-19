<?php

namespace Empiriq\BinanceTradeBundle\Derivatives\FuturesCoinM\Clients;

use Empiriq\BinanceTradeBundle\Common\Clients\Rest\RestClient;
use Empiriq\BinanceTradeBundle\Common\Configs\RestConfig;
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
 * The actual base URI is provided via {@see RestConfig}.
 *
 * @see https://developers.binance.com/docs/derivatives/coin-margined-futures/general-info
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
