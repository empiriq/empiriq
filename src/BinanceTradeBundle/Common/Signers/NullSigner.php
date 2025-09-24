<?php

namespace Empiriq\BinanceTradeBundle\Common\Signers;

use Empiriq\BinanceTradeBundle\Common\Exceptions\Configuration\ConfigurationException;
use Empiriq\BinanceTradeBundle\Common\Interfaces\SignerInterface;

readonly class NullSigner implements SignerInterface
{
    public function createSignature(array $params): string
    {
        throw new ConfigurationException('null signer');
    }
}
