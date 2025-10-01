<?php

namespace Empiriq\BinanceManagerBundle;

use Empiriq\BinanceManagerBundle\DependencyInjection\BinanceManagerExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @api Provides repositories for interaction with the Binance state locally
 */
final class BinanceManagerBundle extends Bundle
{
    #[\Override]
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new BinanceManagerExtension();
    }
}
