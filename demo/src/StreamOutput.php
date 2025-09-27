<?php

namespace Empiriq\Demo;

use React\Socket\ConnectionInterface;
use Symfony\Component\Console\Output\Output;

final class StreamOutput extends Output
{
    public function __construct(
        private readonly ConnectionInterface $connection
    ) {
        parent::__construct();
    }

    #[\Override]
    protected function doWrite(string $message, bool $newline): void
    {
        $this->connection->write($message . ($newline ? PHP_EOL : ''));
    }
}
