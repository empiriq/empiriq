<?php

namespace Empiriq\BinanceTradeBundle\Tests\Derivatives\FuturesUsdM;

use _PHPStan_2d0955352\Nette\Neon\Exception;
use Bramus\Monolog\Formatter\ColoredLineFormatter;
use Empiriq\BinanceTradeBundle\Common\Helpers\Serializer;
use Empiriq\BinanceTradeBundle\Common\Signers\HmacSigner;
use Empiriq\BinanceTradeBundle\Connector;
use Empiriq\BinanceTradeBundle\Derivatives\FuturesUsdM\FuturesUsdMTransportFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

use function React\Async\await;

class TransportTest extends TestCase
{
    public function testAllMethods(): void
    {
        $this->assertTrue(true);
    }
}
