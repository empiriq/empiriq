<?php

namespace Empiriq\BinanceBackBundle\Common\Interfaces\Receivers;

use Empiriq\BinanceBackBundle\Common\Interfaces\ReceiverInterface;
use Empiriq\BinanceBackBundle\Common\Interfaces\Streams\FuturesUmStreamInterface;

/**
 * @internal
 */
interface FuturesUmReceiverInterface extends ReceiverInterface
{
    public function addStream(FuturesUmStreamInterface $stream): FuturesUmReceiverInterface;
}
