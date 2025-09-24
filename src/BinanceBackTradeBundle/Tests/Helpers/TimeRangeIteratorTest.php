<?php

namespace Empiriq\BinanceBackTradeBundle\Tests\Helpers;

use DateTime;
use DateTimeZone;
use Empiriq\BinanceContracts\Spot\Spot\Events\Market\TradeEvent;
use Empiriq\BinanceBackTradeBundle\Common\Helpers\CsvIterator;
use Empiriq\BinanceBackTradeBundle\Common\Helpers\Serializer;
use Empiriq\BinanceBackTradeBundle\Common\Helpers\TimeRangeIterator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class TimeRangeIteratorTest extends TestCase
{
    public function testTimeRangeIterator()
    {
        $serializer = new Serializer();
        $iterator = new CsvIterator(
            __DIR__ . '/../working_dir/data/spot/daily/trades/AAVEBNB/AAVEBNB-trades-2024-01-01.csv',
            fn(array $data): TradeEvent => $serializer->denormalize(
                data: $data,
                type: TradeEvent::class,
                context: [
                    AbstractNormalizer::FILTER_BOOL => true,
                    AbstractNormalizer::DEFAULT_CONSTRUCTOR_ARGUMENTS => [
                        TradeEvent::class => ['symbol' => 'AAVEBNB'],
                    ],
                ],
            )
        );
        $utcFrom = new DateTime('2024-01-01 00:00:00', new DateTimeZone('UTC'));
        $utcTo = new DateTime('2024-01-01 00:00:40', new DateTimeZone('UTC'));
        $iterator = new TimeRangeIterator($iterator, $utcFrom, $utcTo);

        $this->assertCount(1, $iterator);
    }
}
