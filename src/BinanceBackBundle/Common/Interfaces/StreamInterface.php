<?php

namespace Empiriq\BinanceBackBundle\Common\Interfaces;

use Iterator;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @internal
 */
interface StreamInterface
{
    /**
     * @return Iterator<int, object>
     */
    public function run(SerializerInterface $serializer): Iterator;
}
