<?php

namespace Empiriq\BinanceBackBundle\Common\Interfaces;

/**
 * @internal
 */
interface CsvLoaderInterface
{
    public function load(string $uri): string;
}
