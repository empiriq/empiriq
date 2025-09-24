<?php

namespace Empiriq\BinanceBackTradeBundle\Tests\Helpers;

use Empiriq\BinanceContracts\Spot\Spot\Events\Market\TradeEvent;
use Empiriq\BinanceBackTradeBundle\Common\Helpers\CsvIterator;
use Empiriq\BinanceBackTradeBundle\Common\Helpers\Serializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class CsvIteratorTest extends TestCase
{
    public function testIteration(): void
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
        $array = iterator_to_array($iterator);

        $this->assertCount(760, $array);
        $this->assertEquals(
            new TradeEvent(
                time: 1704067239541,
                symbol: 'AAVEBNB',
                price: 0.3491,
                quantity: 1.822,
                isBuyerMaker: false,
            ),
            $array[0]
        );
        $this->assertEquals(
            new TradeEvent(
                time: 1704071253225,
                symbol: 'AAVEBNB',
                price: 0.3502,
                quantity: 1.813,
                isBuyerMaker: true,
            ),
            $array[42]
        );
    }
}
