<?php

namespace Empiriq\BinanceBackTradeBundle\Common;

use AppendIterator;
use Closure;
use DatePeriod;
use DateTimeInterface;
use Empiriq\BinanceBackTradeBundle\Common\Helpers\CsvIterator;
use Empiriq\BinanceBackTradeBundle\Common\Helpers\TimeRangeIterator;
use Empiriq\BinanceBackTradeBundle\Common\Interfaces\CsvLoaderInterface;
use Empiriq\BinanceBackTradeBundle\Common\Interfaces\StreamInterface;
use Iterator;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Base class for implementing historical data.
 *
 * Provides iteration over historical market data using date-based URI generation,
 * lazy CSV loading, and event deserialization.
 * @internal
 */
abstract class AbstractStream implements StreamInterface
{
    /**
     * @return Closure(DateTimeInterface): string Closure that maps a \DateTimeInterface to a data URI.
     */
    abstract protected function getUri(): Closure;

    /**
     * @return DatePeriod Provide the time range for data collection.
     */
    abstract protected function getDatePeriod(): DatePeriod;

    abstract protected function getLoader(): CsvLoaderInterface;

    /**
     * @return Closure(array): object Handler that will deserialize CSV rows into Event objects.
     */
    abstract protected function getHandler(SerializerInterface $serializer): Closure;

    /**
     * Iterates over all historical data within the specified date period.
     *
     * Each CSV source is loaded based on URIs generated from the date range,
     * deserialized using the provided serializer, and aggregated into a single iterator.
     *
     * @return Iterator<int, object>
     */
    public function run(SerializerInterface $serializer): Iterator
    {
        return new TimeRangeIterator(
            array_reduce(
                array_map($this->getUri(), iterator_to_array($this->getDatePeriod())),
                function (AppendIterator $aggregator, string $uri) use ($serializer): Iterator {
                    $aggregator->append(
                        new CsvIterator($this->getLoader()->load($uri), $this->getHandler($serializer))
                    );
                    return $aggregator;
                },
                new AppendIterator()
            ),
            $this->getDatePeriod()->start,
            $this->getDatePeriod()->end
        );
    }
}
