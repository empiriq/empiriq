<?php

namespace Empiriq\BinanceBackTradeBundle\Tests\Helpers;

use Empiriq\BinanceContracts\Spot\Spot\Events\Market\TradeEvent;
use Empiriq\BinanceBackTradeBundle\Common\Helpers\Serializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class SerializerTest extends TestCase
{
    public function testSerializer(): void
    {
        $row = ['3658842', '0.37090000', '0.08800000', '0.03263920', '1704153594207', 'False', 'False'];
        $symbol = 'AAVEBNB';
        $serializer = new Serializer();

        $this->assertEquals(
            new TradeEvent(
                time: 1704153594207,
                symbol: $symbol,
                price: 0.3709,
                quantity: 0.088,
                isBuyerMaker: false,
            ),
            $serializer->denormalize($row, TradeEvent::class, null, [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                AbstractNormalizer::FILTER_BOOL => true,
                AbstractNormalizer::DEFAULT_CONSTRUCTOR_ARGUMENTS => [
                    TradeEvent::class => ['symbol' => $symbol],
                ],
            ])
        );
    }
}
