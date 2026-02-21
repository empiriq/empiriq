<?php

namespace Empiriq\BinanceBackBundle;

use Empiriq\BinanceBackBundle\DependencyInjection\BinanceBackExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @api Provides interaction with the Binance History Data
 */
final class BinanceBackBundle extends Bundle
{
    #[\Override]
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new BinanceBackExtension();
    }
}
