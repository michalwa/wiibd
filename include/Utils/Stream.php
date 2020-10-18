<?php

namespace Utils;

use ArrayIterator;
use \Iterator;

/**
 * Allows for convenient execution of batch operations on iterables
 */
class Stream implements Iterator {

    /**
     * The source iterator
     * @var Iterator $source
     */
    private $source;

    /**
     * The function to feed elements of the source iterator through
     * @var null|callable
     */
    private $mapFunction = null;

    /**
     * Constructs a new stream from the given source
     *
     * @param Iterator|array $source the source iterator or array
     * @param null|callable $mapFunction the function to feed elements of the source iterator through
     */
    private function __construct($source, ?callable $mapFunction = null) {
        $this->source = is_array($source) ? new ArrayIterator($source) : $source;
        $this->mapFunction = $mapFunction;
    }

    /**
     * Pipes this stream into another stream that will map each element by
     * feeding it throught the given mapping function
     *
     * @param callable $mapFunction the function to feed elements of the stream through
     */
    public function map(callable $mapFunction) {
        return new Stream($this, $mapFunction);
    }

    /**
     * Returns the first found element from this stream
     */
    public function get() {
        $this->rewind();
        return $this->current();
    }

    /**
     * {@inheritDoc}
     */
    public function current() {
        if($this->mapFunction) {
            return call_user_func($this->mapFunction, $this->source->current());
        }
        return $this->source->current();
    }

    /**
     * {@inheritDoc}
     */
    public function key() {
        return $this->source->key();
    }

    /**
     * {@inheritDoc}
     */
    public function next() {
        $this->source->next();
    }

    /**
     * {@inheritDoc}
     */
    public function rewind() {
        $this->source->rewind();
    }

    /**
     * {@inheritDoc}
     */
    public function valid() {
        return $this->source->valid();
    }

    /**
     * Shorthand for `iterator_to_array($this)`
     */
    public function toArray(): array {
        return iterator_to_array($this);
    }

    /**
     * Constructs and returns a new stream from the given iterable
     *
     * @param Iterator $iterable
     */
    public static function begin($iterator): self {
        return new Stream($iterator);
    }

}
