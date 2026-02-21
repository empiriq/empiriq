<?php

namespace Empiriq\BinanceBackBundle\Common\Interfaces\Receivers;

use Empiriq\BinanceBackBundle\Common\Interfaces\ReceiverInterface;
use Empiriq\BinanceBackBundle\Common\Interfaces\Streams\SpotStreamInterface;

/**
 * @internal
 */
interface SpotReceiverInterface extends ReceiverInterface
{
    public function addStream(SpotStreamInterface $stream): SpotReceiverInterface;
}
