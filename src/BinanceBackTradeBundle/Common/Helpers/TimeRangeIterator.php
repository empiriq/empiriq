<?php

namespace Empiriq\BinanceBackTradeBundle\Common\Helpers;

use DateTimeInterface;
use Iterator;

/**
 * @internal
 */
class TimeRangeIterator implements Iterator
{
    private Iterator $iterator;
    private int $from;
    private int $to;
    private bool $stopped = false;
    private int $key = 0;

    public function __construct(Iterator $iterator, DateTimeInterface $utcFrom, DateTimeInterface $utcTo)
    {
        $this->iterator = $iterator;
        $this->from = (int)($utcFrom->format('Uu') / 1000);
        $this->to = (int)($utcTo->format('Uu') / 1000);
    }

    public function current(): mixed
    {
        return $this->iterator->current();
    }

    public function key(): int
    {
        return $this->key;
    }

    public function next(): void
    {
        $this->key++;
        $this->iterator->next();
        $this->skip();
    }

    public function rewind(): void
    {
        $this->key = 0;
        $this->stopped = false;
        $this->iterator->rewind();
        $this->skip();
    }

    public function valid(): bool
    {
        return !$this->stopped && $this->iterator->valid();
    }

    private function skip(): void
    {
        while ($this->iterator->valid()) {
            $item = $this->iterator->current();
            if (!isset($item->time)) {
                $this->iterator->next();
                continue;
            }
            if ($item->time < $this->from) {
                $this->iterator->next();
                continue;
            }
            if ($item->time > $this->to) {
                $this->stopped = true;
                return;
            }
            return;
        }
    }
}
