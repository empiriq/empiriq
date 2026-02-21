<?php

namespace Empiriq\BinanceBackBundle\Spot\Spot\Receivers;

use Empiriq\BinanceBackBundle\Common\AbstractReceiver;
use Empiriq\BinanceBackBundle\Common\Interfaces\Receivers\SpotReceiverInterface;
use Empiriq\BinanceBackBundle\Common\Interfaces\Streams\SpotStreamInterface;

class SpotReceiver extends AbstractReceiver implements SpotReceiverInterface
{
    /**
     * @param SpotStreamInterface[] $streams
     */
    public function __construct(private array $streams = [])
    {
    }

    public function addStream(SpotStreamInterface $stream): SpotReceiverInterface
    {
        $this->streams[] = $stream;

        return $this;
    }

    /**
     * @return SpotStreamInterface[]
     */
    protected function getStreams(): array
    {
        return $this->streams;
    }
}
