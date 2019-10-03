<?php

namespace Meta;

/**
 * Base class for annotation types
 */
abstract class Annotation {

    /**
     * Creates and returns an annotation of this type
     * 
     * @param object|null $object An object of the class $item is attached to
     *  for which the annotation should be instantiated (or `null`).
     * @param \Reflector $item The item the annotation is attached to
     * @param $params The parameters passed to the annotation expression
     */
    public static abstract function instantiate(
        ?object $object,
        \Reflector $item,
        $params
    ): Annotation;

}
