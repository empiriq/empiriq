<?php

namespace Empiriq\BinanceBackTradeBundle\Common\Interfaces\Receivers;

use Empiriq\BinanceBackTradeBundle\Common\Interfaces\ReceiverInterface;
use Empiriq\BinanceBackTradeBundle\Common\Interfaces\Streams\SpotStreamInterface;

/**
 * @internal
 */
interface SpotReceiverInterface extends ReceiverInterface
{
    public function addStream(SpotStreamInterface $stream): SpotReceiverInterface;
}
