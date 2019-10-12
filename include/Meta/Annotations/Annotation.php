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
     * Constructs an annotation of the appropriate type
     * 
     * @param Reflector $item The item the annotation is attached to
     * @param mixed[] $params The parameters passed to the annotation expression
     */
    public function __construct(Reflector $item) {
        $this->item = $item;
    }

    /**
     * Returns the annotated item
     */
    public function getItem(): Reflector {
        return $this->item;
    }

}
