<?php

namespace Empiriq\BinanceBackTradeBundle\Spot\Spot\Receivers;

use Empiriq\BinanceBackTradeBundle\Common\AbstractReceiver;
use Empiriq\BinanceBackTradeBundle\Common\Interfaces\Receivers\FuturesUmReceiverInterface;
use Empiriq\BinanceBackTradeBundle\Common\Interfaces\Streams\FuturesUmStreamInterface;

class FuturesUmReceiver extends AbstractReceiver implements FuturesUmReceiverInterface
{
    /**
     * @param FuturesUmStreamInterface[] $streams
     */
    public function __construct(private array $streams = [])
    {
    }

    public function addStream(FuturesUmStreamInterface $stream): FuturesUmReceiverInterface
    {
        $this->streams[] = $stream;

        return $this;
    }

    /**
     * @return FuturesUmStreamInterface[]
     */
    protected function getStreams(): array
    {
        return $this->streams;
    }
}
