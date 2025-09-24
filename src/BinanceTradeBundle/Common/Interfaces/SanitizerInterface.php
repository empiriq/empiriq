<?php

namespace Empiriq\BinanceTradeBundle\Common\Interfaces;

/**
 * Sanitizes sensitive request data before logging.
 * Supports full or partial masking of values.
 */
interface SanitizerInterface
{
    /**
     * Sanitize request array by masking sensitive keys.
     *
     * @param array $request
     * @return array
     */
    public function sanitize(array $request): array;
}
