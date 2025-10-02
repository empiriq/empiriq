<?php

namespace Empiriq\BinanceTradeBundle\Common\Signers;

use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;
use RuntimeException;

/**
 * @api Ed25519 signer that creates request signatures using a private key.
 */
final readonly class Ed25519Signer implements SignerInterface
{
    public function __construct(
        private string $privateKeyPath,
        private ?string $passPhrase = null,
    ) {
    }

    #[\Override]
    public function createSignature(array $params): string
    {
        $keyContent = file_get_contents($this->privateKeyPath);
        if ($keyContent === false) {
            throw new RuntimeException(sprintf('Private key file not found: %s', $this->privateKeyPath));
        }
        $privateKey = openssl_pkey_get_private($keyContent, $this->passPhrase);
        if ($privateKey === false) {
            throw new RuntimeException('Unable to load private key');
        }
        ksort($params);
        /**
         * @psalm-suppress NullArgument algorithm argument must be null for "ed25519"
         * @see https://docs.openssl.org/master/man3/EVP_DigestSignInit/
         */
        $ok = openssl_sign(
            data: http_build_query($params),
            signature: $binarySignature,
            private_key: $privateKey,
            algorithm: null
        );
        if ($ok === false) {
            throw new RuntimeException('Failed to create Ed25519 signature');
        }

        return base64_encode($binarySignature);
    }
}
