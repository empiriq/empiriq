<?php

namespace Empiriq\BinanceRealBundle\Common\Signers;

use Empiriq\BinanceRealBundle\Common\Exceptions\Configuration\ConfigurationException;
use Empiriq\BinanceRealBundle\Common\Interfaces\SignerInterface;

/**
 * @api Placeholder signer that always throws a configuration error when used.
 */
final readonly class NullSigner implements SignerInterface
{
    #[\Override]
    public function createSignature(array $params): string
    {
        throw new ConfigurationException('null signer');
    }
}
