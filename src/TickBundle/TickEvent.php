<?php

namespace Empiriq\TickBundle;

/**
 * Periodic tick event payload.
 *
 * @api
 */
final readonly class TickEvent
{
    public function __construct(
        public \DateTimeImmutable $time,
        public float $interval,
    ) {
    }
}
