<?php

namespace Empiriq\TickerBundle;

interface ClockInterface
{
    public function start(): void;
    public function stop(): void;
}
