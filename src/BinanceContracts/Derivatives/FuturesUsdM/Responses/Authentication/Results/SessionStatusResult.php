<?php

namespace Empiriq\BinanceContracts\Derivatives\FuturesUsdM\Responses\Authentication\Results;

readonly class SessionStatusResult
{
    /**
     * @param string|null $apiKey null if the connection is not authenticated
     * @param int|null $authorizedSince null if the connection is not authenticated
     * @param int $connectedSince
     * @param bool $returnRateLimits
     * @param int $serverTime
     * @param bool $userDataStream is User Data Stream subscription active?
     */
    public function __construct(
        public ?string $apiKey,
        public ?int $authorizedSince,
        public int $connectedSince,
        public bool $returnRateLimits,
        public int $serverTime,
        public bool $userDataStream,
    ) {
    }
}
