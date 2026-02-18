<?php

namespace Empiriq\BinanceTradeBundle\Common\Configs;

/**
 * Configuration DTO for Binance REST API clients.
 *
 * Encapsulates all runtime configuration required to communicate with Binance REST endpoints.
 * Constructed by the DI container from bundle configuration.
 *
 * @api
 */
final readonly class RestConfig
{
    /**
     * @param string $uri Base REST API URI.
     * @param string $apiKey Binance API key used for authenticated requests.
     * @param float $timeout Connection timeout in seconds.
     */
    public function __construct(
        public string $uri,
        public string $apiKey,
        public float $timeout = 5.0,
    ) {
    }
}
