<?php

namespace Empiriq\BinanceBackTradeBundle\Spot\Spot\Receivers\Streams\Spot;

use Closure;
use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Empiriq\BinanceContracts\Spot\Spot\Events\Market\TradeEvent;
use Empiriq\BinanceBackTradeBundle\Common\AbstractStream;
use Empiriq\BinanceBackTradeBundle\Common\Helpers\CsvLoader;
use Empiriq\BinanceBackTradeBundle\Common\Interfaces\CsvLoaderInterface;
use Empiriq\BinanceBackTradeBundle\Common\Interfaces\Streams\SpotStreamInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Stream for loading historical spot trade events from Binance.
 *
 * Constructs URIs to download zipped CSV trade data for a specific symbol
 * over a given date range and deserializes them into TradeEvent objects.
 */
class TradeStream extends AbstractStream implements SpotStreamInterface
{
    /**
     * @param string $symbol Trading pair symbol (e.g. 'BTCUSDT')
     * @param string $start Start date (inclusive), in any timezone
     * @param string $end End date (exclusive), in any timezone
     */
    public function __construct(
        private string $symbol,
        private string $start,
        private string $end,
        private CsvLoaderInterface $loader = new CsvLoader()
    ) {
    }

    /**
     * Returns a closure that builds a Binance data URL for the given date.
     *
     * @return Closure(DateTimeInterface): string
     */
    protected function getUri(): Closure
    {
        return fn(DateTimeInterface $date) => "https://data.binance.vision/data/"
            . "spot/daily/trades/{$this->symbol}/{$this->symbol}-trades-{$date->format('Y-m-d')}.zip";
    }

    /**
     * Returns the date range over which historical data should be loaded.
     *
     * @return DatePeriod
     * @throws \Exception
     */
    protected function getDatePeriod(): DatePeriod
    {
        return new DatePeriod(
            start: new DateTimeImmutable($this->start, new DateTimeZone('UTC')),
            interval: new DateInterval('P1D'),
            end: new DateTimeImmutable($this->end, new DateTimeZone('UTC')),
        );
    }

    protected function getLoader(): CsvLoaderInterface
    {
        return $this->loader;
    }

    /**
     * Returns a closure that deserializes a row of CSV data into a TradeEvent.
     *
     * @return Closure(array): TradeEvent
     */
    protected function getHandler(SerializerInterface $serializer): Closure
    {
        return fn(array $data): TradeEvent => $serializer->denormalize(
            data: $data,
            type: TradeEvent::class,
            context: [
                AbstractNormalizer::FILTER_BOOL => true,
                AbstractNormalizer::DEFAULT_CONSTRUCTOR_ARGUMENTS => [
                    TradeEvent::class => ['symbol' => $this->symbol],
                ],
            ],
        );
    }
}
