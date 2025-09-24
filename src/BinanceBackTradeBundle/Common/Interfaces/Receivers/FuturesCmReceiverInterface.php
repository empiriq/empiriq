<?php

namespace Empiriq\BinanceBackTradeBundle\Common\Interfaces\Receivers;

use Empiriq\BinanceBackTradeBundle\Common\Interfaces\ReceiverInterface;
use Empiriq\BinanceBackTradeBundle\Common\Interfaces\Streams\FuturesCmStreamInterface;

/**
 * @internal
 */
interface FuturesCmReceiverInterface extends ReceiverInterface
{
    public function addStream(FuturesCmStreamInterface $stream): FuturesCmReceiverInterface;
}
