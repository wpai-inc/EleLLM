<?php

namespace WpAi\EleLLM\Responses;

use Generator;
use IteratorAggregate;

/**
 * @template T
 */
class Stream implements IteratorAggregate
{
    /**
     * @var Generator<T>
     */
    private Generator $stream;

    /**
     * @param  Generator<T>  $stream
     */
    public function __construct(Generator $stream)
    {
        $this->stream = $stream;
    }

    /**
     * Get the iterator for the stream.
     *
     * @return Generator<T>
     */
    public function getIterator(): Generator
    {
        return $this->stream;
    }
}
