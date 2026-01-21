<?php

namespace Empiriq\BinanceTradeBundle\Common\Configs;

/**
 * Configuration DTO for Web Socket API clients.
 *
 * Encapsulates all runtime configuration required to communicate with Binance Web Socket API endpoints.
 */
final class WebSocketConfig
{
    public function __construct(
        public string $uri,
        public string $apiKey,
        public float $timeout = 5.0,
    ) {
    }
}
