<?php

namespace Empiriq\BinanceBackTradeBundle\Spot\Spot\Receivers;

use Empiriq\BinanceBackTradeBundle\Common\AbstractReceiver;
use Empiriq\BinanceBackTradeBundle\Common\Interfaces\Receivers\FuturesCmReceiverInterface;
use Empiriq\BinanceBackTradeBundle\Common\Interfaces\Streams\FuturesCmStreamInterface;

class FuturesCmReceiver extends AbstractReceiver implements FuturesCmReceiverInterface
{
    /**
     * @param FuturesCmStreamInterface[] $streams
     */
    public function __construct(private array $streams = [])
    {
    }

    public function addStream(FuturesCmStreamInterface $stream): FuturesCmReceiverInterface
    {
        $this->streams[] = $stream;

        return $this;
    }

    /**
     * @return FuturesCmStreamInterface[]
     */
    protected function getStreams(): array
    {
        return $this->streams;
    }
}
