<?php

namespace Empiriq\BinanceManagerBundle\Common;

/**
 * Ring buffer (circular buffer) implementation.
 *
 * @template T
 * @internal
 */
class RingBuffer
{
    /** @var array<int, T|null> Internal storage */
    private array $buffer;

    /** @var int Maximum buffer size */
    private int $size;

    /** @var int Current write position */
    private int $pointer = 0;

    /** @var int Number of elements currently stored */
    private int $count = 0;

    /**
     * @param int $size Fixed buffer size
     */
    public function __construct(int $size)
    {
        $this->size = $size;
        $this->buffer = array_fill(0, $size, null);
    }

    /**
     * Add a value to the buffer. If the buffer is full, the oldest value will be overwritten.
     *
     * @param T $value
     */
    public function add(mixed $value): void
    {
        $this->buffer[$this->pointer] = $value;
        $this->pointer = ($this->pointer + 1) % $this->size;

        if ($this->count < $this->size) {
            $this->count++;
        }
    }

    /**
     * Get all values in the correct order (oldest to newest).
     *
     * @return array<int, T>
     */
    public function all(): array
    {
        if ($this->count < $this->size) {
            /** @var array<int, T> */
            return array_slice($this->buffer, 0, $this->count);
        }

        /** @var array<int, T> */
        return array_merge(
            array_slice($this->buffer, $this->pointer),
            array_slice($this->buffer, 0, $this->pointer)
        );
    }
}
