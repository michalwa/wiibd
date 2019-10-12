<?php

namespace Meta\Annotations;

use \ReflectionMethod;
use Meta\Annotations\Annotations;

/**
 * An annotated extension of `ReflectionMethod`
 */
class ReflectionMethodAnnotated extends ReflectionMethod {

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
     * @param ReflectionMethod $method The reflection object to convert
     * @param array $annotationAliases Annotation class aliases passed to `Annotations::parseAll()`
     */
    public static function from(ReflectionMethod $method, $annotationAliases = []): ReflectionMethodAnnotated {
        return new self($method->class, $method->getName(), $annotationAliases);
    }

}
