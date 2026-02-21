<?php

namespace Empiriq\TickerBundle;

final readonly class TickEvent
{
    public function __construct(
        public \DateTimeImmutable $time,
        public float $interval,
    ) {
    }
}
