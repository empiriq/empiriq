<?php

namespace Empiriq\BinanceBackTradeBundle;

use Empiriq\BinanceBackTradeBundle\DependencyInjection\BinanceBackTradeExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @api Provides interaction with the Binance History Data
 */
final class BinanceBackTradeBundle extends Bundle
{
    #[\Override]
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new BinanceBackTradeExtension();
    }
}
