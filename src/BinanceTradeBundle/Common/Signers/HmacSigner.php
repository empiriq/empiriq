<?php

namespace Empiriq\BinanceTradeBundle\Common\Signers;

use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;

readonly class HmacSigner implements SignerInterface
{
    public function __construct(
        private string $secretKey,
    ) {
    }

    public function createSignature(array $params): string
    {
        ksort($params);

        return hash_hmac('sha256', http_build_query($params), $this->secretKey);
    }
}
