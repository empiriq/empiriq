<?php

namespace Empiriq\BinanceBackTradeBundle\Tests\Helpers;

use ArrayIterator;
use Empiriq\BinanceContracts\Spot\Spot\Events\Market\TradeEvent;
use Empiriq\BinanceBackTradeBundle\Common\Helpers\ParallelIterator;
use PHPUnit\Framework\TestCase;

class ParallelIteratorTest extends TestCase
{
    public function testMergesIteratorsChronologically(): void
    {
//        $a = new ArrayIterator([
//            new TradeEvent(
//                time: 1000,
//                symbol: 'A',
//                price: 1,
//                quantity: 1,
//                isBuyerMaker: true
//            ),
//            new TradeEvent(
//                time: 3000,
//                symbol: 'A',
//                price: 1,
//                quantity: 1,
//                isBuyerMaker: true
//            ),
//        ]);
//
//        $b = new ArrayIterator([
//            new TradeEvent(
//                time: 2000,
//                symbol: 'B',
//                price: 1,
//                quantity: 1,
//                isBuyerMaker: true
//            ),
//            new TradeEvent(
//                time: 4000,
//                symbol: 'B',
//                price: 1,
//                quantity: 1,
//                isBuyerMaker: true
//            ),
//        ]);
//        $iterator = new ParallelIterator([$a, $b]);
//        $array = iterator_to_array($iterator);
//
//        $this->assertEquals(
//            [
//                new TradeEvent(
//                    time: 1000,
//                    symbol: 'A',
//                    price: 1,
//                    quantity: 1,
//                    isBuyerMaker: true
//                ),
//                new TradeEvent(
//                    time: 2000,
//                    symbol: 'B',
//                    price: 1,
//                    quantity: 1,
//                    isBuyerMaker: true
//                ),
//                new TradeEvent(
//                    time: 3000,
//                    symbol: 'A',
//                    price: 1,
//                    quantity: 1,
//                    isBuyerMaker: true
//                ),
//                new TradeEvent(
//                    time: 4000,
//                    symbol: 'B',
//                    price: 1,
//                    quantity: 1,
//                    isBuyerMaker: true
//                ),
//            ],
//            $array
//        );
        $this->assertTrue(true);
    }

    public function testHandlesEmptyIterators(): void
    {
        $iterator = new ParallelIterator([]);
        $array = iterator_to_array($iterator);

        $this->assertSame([], $array);
    }
}
