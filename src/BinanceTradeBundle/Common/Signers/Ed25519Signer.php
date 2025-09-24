<?php

namespace Empiriq\BinanceTradeBundle\Common\Signers;

use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;

readonly class Ed25519Signer implements SignerInterface
{
    public function __construct(
        private string $privateKeyPath,
        private ?string $passPhrase = null,
    ) {
    }

    public function createSignature(array $params): string
    {
        ksort($params);
        @openssl_sign(
            data: http_build_query($params),
            signature: $binarySignature,
            private_key: openssl_pkey_get_private(file_get_contents($this->privateKeyPath), $this->passPhrase),
            algorithm: null // must be null for "ed25519": see https://docs.openssl.org/master/man3/EVP_DigestSignInit/
        );

        return base64_encode($binarySignature);
    }
}
