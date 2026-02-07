<?php

namespace Empiriq\TickerBundle;

final readonly class TickEvent
{
    public function __construct(
        public string $period,
        public \DateTimeImmutable $time,
    ) {
    }
}
