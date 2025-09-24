<?php

namespace Empiriq\BinanceBackTradeBundle\Common\Interfaces;

/**
 * @internal
 */
interface CsvLoaderInterface
{
    public function load(string $uri): string;
}
