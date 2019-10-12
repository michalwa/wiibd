<?php

namespace Meta;

use \Reflector;

/**
 * Base class for annotation types
 */
abstract class Annotation {

    /**
     * Creates and returns an annotation of this type
     * 
     * @param Reflector $item The item the annotation is attached to
     * @param object|null $object An object of the class $item is attached to
     *  for which the annotation should be instantiated (or `null`).
     * @param mixed[] $params The parameters passed to the annotation expression
     */
    public static abstract function instantiate(Reflector $item, ?object $object, $params): Annotation;

}
