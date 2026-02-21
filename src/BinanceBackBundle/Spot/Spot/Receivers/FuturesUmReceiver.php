<?php

namespace Empiriq\BinanceBackBundle\Spot\Spot\Receivers;

use Empiriq\BinanceBackBundle\Common\AbstractReceiver;
use Empiriq\BinanceBackBundle\Common\Interfaces\Receivers\FuturesUmReceiverInterface;
use Empiriq\BinanceBackBundle\Common\Interfaces\Streams\FuturesUmStreamInterface;

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
