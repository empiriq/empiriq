<?php

namespace Empiriq\BinanceTradeBundle\Common\Signers;

use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;

/**
 * @api HMAC-SHA256 signer that creates request signatures using a secret key.
 */
final readonly class HmacSigner implements SignerInterface
{
    public function __construct(
        private string $secretKey,
    ) {
    }

    #[\Override]
    public function createSignature(array $params): string
    {
        ksort($params);

        return hash_hmac('sha256', http_build_query($params), $this->secretKey);
    }
}
