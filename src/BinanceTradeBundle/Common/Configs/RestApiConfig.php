<?php

namespace Empiriq\BinanceTradeBundle\Common\Configs;

/**
 * Configuration DTO for Binance REST API clients.
 *
 * Encapsulates all runtime configuration required to communicate with Binance REST endpoints.
 */
final class RestApiConfig
{
    /**
     * @param string $uri Base REST API URI.
     * @param string $apiKey Binance API key used for authenticated requests.
     * @param float $resolverTimeout Connection resolver timeout in seconds.
     */
    public function __construct(
        public string $uri,
        public string $apiKey,
        public float $resolverTimeout = 5.0,
    ) {
    }
}
