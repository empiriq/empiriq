<?php

namespace Empiriq\Logging;

use Monolog\LogRecord;

/**
 * Matches log records by message prefix.
 */
final class RecordMessagePrefixPredicate
{
    public function __construct(
        private readonly string $prefix
    ) {
    }

    public function __invoke(LogRecord $record): bool
    {
        return str_starts_with($record->message, $this->prefix);
    }
}
