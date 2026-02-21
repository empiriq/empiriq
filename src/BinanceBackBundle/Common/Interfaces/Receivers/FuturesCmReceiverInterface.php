<?php

namespace Empiriq\BinanceBackBundle\Common\Interfaces\Receivers;

use Empiriq\BinanceBackBundle\Common\Interfaces\ReceiverInterface;
use Empiriq\BinanceBackBundle\Common\Interfaces\Streams\FuturesCmStreamInterface;

/**
 * @internal
 */
interface FuturesCmReceiverInterface extends ReceiverInterface
{
    public function addStream(FuturesCmStreamInterface $stream): FuturesCmReceiverInterface;
}
