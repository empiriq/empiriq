<?php

namespace Empiriq\BinanceBackTradeBundle\Common\Helpers;

use Iterator;

/**
 * @internal
 */
class ParallelIterator implements Iterator
{
    private int $key = 0;

    public function __construct(private readonly array $iterators)
    {
    }

    public function current(): object
    {
        return $this->currentIterator()->current();
    }

    public function rewind(): void
    {
        $this->key = 0;
        foreach ($this->iterators as $iterator) {
            $iterator->rewind();
        }
    }

    public function key(): int
    {
        return $this->key;
    }

    public function next(): void
    {
        $this->key++;
        $this->currentIterator()->next();
    }

    public function valid(): bool
    {
        return array_any($this->iterators, fn(Iterator $item) => $item->valid());
    }

    private function currentIterator(): Iterator
    {
        return array_reduce(
            $this->iterators,
            fn(Iterator $carry, Iterator $item): Iterator => ($item->current()->time < $carry->current()->time)
                ? $item : $carry,
            array_find($this->iterators, fn(Iterator $item): bool => $item->valid())
        );
    }
}
