<?php

namespace Empiriq\BinanceTradeBundle\Common\Signers;

use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;
use RuntimeException;

/**
 * @api RSA-SHA256 signer that creates request signatures using a secret key.
 */
final readonly class RsaSigner implements SignerInterface
{
    public function __construct(
        private string $privateKeyPath,
        private ?string $passPhrase = null,
    ) {
    }

    #[\Override]
    public function createSignature(array $params): string
    {
        $pem = file_get_contents($this->privateKeyPath);
        if ($pem === false) {
            throw new RuntimeException("Failed to read private key file: {$this->privateKeyPath}");
        }
        $privateKey = openssl_pkey_get_private($pem, $this->passPhrase);
        if ($privateKey === false) {
            throw new RuntimeException("Failed to load private key from {$this->privateKeyPath}");
        }
        ksort($params);
        $ok = openssl_sign(
            data: http_build_query($params),
            signature: $binarySignature,
            private_key: $privateKey,
            algorithm: OPENSSL_ALGO_SHA256
        );
        if ($ok === false) {
            throw new RuntimeException('Failed to sign data with RSA-SHA256');
        }

        return base64_encode($binarySignature);
    }
}
