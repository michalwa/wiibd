<?php

namespace Meta\Annotations;

use \ReflectionFunction;
use Meta\Annotations\Annotations;

/**
 * An annotated extension of `ReflectionFunction`
 */
class ReflectionFunctionAnnotated extends ReflectionFunction {

    use Annotated;

    /**
     * {@inheritDoc}
     * 
     * @param array $annotationAliases Annotation class aliases passed to `Annotations::parseAll()`
     */
    public function __construct($name, $annotationAliases = []) {
        parent::__construct($name);
        $this->annotations = Annotations::parseAll($this, $this->getDocComment(), $annotationAliases);
    }

    /**
     * Extends the given reflection object into this class
     * 
     * @param ReflectionFunction $function The reflection object to convert
     * @param array $annotationAliases Annotation class aliases passed to `Annotations::parseAll()`
     */
    public static function from(ReflectionFunction $function, $annotationAliases = []): ReflectionFunctionAnnotated {
        return new self($function->getName(), $annotationAliases);
    }

}
