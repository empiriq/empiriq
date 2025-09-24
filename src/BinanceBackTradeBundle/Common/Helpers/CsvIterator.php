<?php

namespace Empiriq\BinanceBackTradeBundle\Common\Helpers;

use Closure;
use Iterator;
use SplFileObject;

/**
 * @internal
 */
class CsvIterator implements Iterator
{
    private Iterator $iterator;
    private Closure $handler;
    private int $key = 0;

    public function __construct(string $file, Closure $handler)
    {
        $this->iterator = new SplFileObject($file);
        $this->iterator->setFlags(
            SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE
        );
        $this->handler = $handler;
    }

    public function current(): object
    {
        return ($this->handler)($this->iterator->current());
    }

    public function key(): int
    {
        return $this->key;
    }

    public function next(): void
    {
        $this->key++;
        $this->iterator->next();
    }

    public function valid(): bool
    {
        while ($this->iterator->valid()) {
            if ($this->iterator->current() && !empty($this->current()->time)) {
                return true;
            }
            $this->key--;
            $this->next();
        }

        return false;
    }

    public function rewind(): void
    {
        $this->key = 0;
        $this->iterator->rewind();
    }
}
