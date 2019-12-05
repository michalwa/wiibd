<?php

namespace Meta\Annotations;

use \Reflector;

/**
 * Base class for annotation types
 */
abstract class Annotation {

    /**
     * The annotated item
     * @var Reflector
     */
    private $item;

    /**
     * The line index offset between the line where the annotation was declared
     * and the first line of the annotated item
     * @var int
     */
    private $lineOffset;

    /**
     * Constructs an annotation for the given item
     * 
     * @param Reflector $item The item the annotation is attached to
     * @param int $lineOffset The line index offset between the line where the annotation was declared and the first line of the annotated item
     */
    public function __construct(Reflector $item, int $lineOffset) {
        $this->item = $item;
        $this->lineOffset = $lineOffset;
    }

    /**
     * Returns the annotated item
     */
    public function getItem(): Reflector {
        return $this->item;
    }

    /**
     * Returns the line index offset between the line where the annotation was declared
     * and the first line of the annotated item
     */
    public function getLineOffset(): int {
        return $this->lineOffset;
    }

    /**
     * Returns whether a single item can be annotated with multiple annotations of this type
     */
    public static abstract function allowMultiple(): bool;

}
