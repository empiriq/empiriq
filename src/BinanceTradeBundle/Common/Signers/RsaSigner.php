<?php

namespace Empiriq\BinanceTradeBundle\Common\Signers;

use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;

readonly class RsaSigner implements SignerInterface
{
    public function __construct(
        private string $privateKeyPath,
        private ?string $passPhrase = null,
    ) {
    }

    public function createSignature(array $params): string
    {
        ksort($params);
        openssl_sign(
            data: http_build_query($params),
            signature: $binarySignature,
            private_key: openssl_pkey_get_private(file_get_contents($this->privateKeyPath), $this->passPhrase),
            algorithm: OPENSSL_ALGO_SHA256
        );

        return base64_encode($binarySignature);
    }
}
