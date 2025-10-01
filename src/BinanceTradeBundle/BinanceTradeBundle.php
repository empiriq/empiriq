<?php

namespace Empiriq\BinanceTradeBundle;

use Empiriq\BinanceTradeBundle\DependencyInjection\BinanceTradeExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @api Provides interaction with the Binance exchange
 */
final class BinanceTradeBundle extends Bundle
{
    #[\Override]
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new BinanceTradeExtension();
    }
}
