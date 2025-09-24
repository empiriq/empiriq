<?php

namespace Empiriq\BinanceBackTradeBundle\Tests\Helpers;

use Empiriq\BinanceBackTradeBundle\Common\Helpers\CsvLoader;
use PHPUnit\Framework\TestCase;

class FileLoaderTest extends TestCase
{
    public function testCachedFileLoad(): void
    {
        $loader = new CsvLoader(__DIR__ . '/../working_dir');
        $this->assertFileExists(
            $loader->load(
                'https://data.binance.vision/data/spot/daily/trades/AAVEBNB/AAVEBNB-trades-2024-01-01.zip'
            )
        );
    }
}
