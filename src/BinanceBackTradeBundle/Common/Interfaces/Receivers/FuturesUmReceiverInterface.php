<?php

namespace Empiriq\BinanceBackTradeBundle\Common\Interfaces\Receivers;

use Empiriq\BinanceBackTradeBundle\Common\Interfaces\ReceiverInterface;
use Empiriq\BinanceBackTradeBundle\Common\Interfaces\Streams\FuturesUmStreamInterface;

/**
 * @internal
 */
interface FuturesUmReceiverInterface extends ReceiverInterface
{
    public function addStream(FuturesUmStreamInterface $stream): FuturesUmReceiverInterface;
}
