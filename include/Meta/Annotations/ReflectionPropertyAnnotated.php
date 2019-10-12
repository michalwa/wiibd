<?php

namespace Meta\Annotations;

use \ReflectionProperty;
use Meta\Annotations\Annotations;

/**
 * An annotated extension of `ReflectionProperty`
 */
class ReflectionPropertyAnnotated extends ReflectionProperty {

    use Annotated;

    /**
     * {@inheritDoc}
     * 
     * @param array $annotationAliases Annotation class aliases passed to `Annotations::parseAll()`
     */
    public function __construct($class, $name, $annotationAliases = []) {
        parent::__construct($class, $name);
        $this->annotations = Annotations::parseAll($this, $this->getDocComment(), $annotationAliases);
    }

    /**
     * Extends the given reflection object into this class
     * 
     * @param ReflectionProperty $property The reflection object to convert
     * @param array $annotationAliases Annotation class aliases passed to `Annotations::parseAll()`
     */
    public static function from(ReflectionProperty $property, $annotationAliases = []): ReflectionPropertyAnnotated {
        return new self($property->class, $property->getName(), $annotationAliases);
    }

}
