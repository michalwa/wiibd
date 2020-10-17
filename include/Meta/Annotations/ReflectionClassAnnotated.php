<?php

namespace Meta\Annotations;

use \ReflectionClass;
use Meta\Annotations\Annotations;

/**
 * An annotated extension of `ReflectionClass`
 */
class ReflectionClassAnnotated extends ReflectionClass {

    use Annotated;

    /**
     * Annotation class aliases
     */
    private $annotationAliases;

    /**
     * {@inheritDoc}
     *
     * @param array $annotationAliases Annotation class aliases passed to `Annotations::parseAll()`
     */
    public function __construct($argument, $annotationAliases = []) {
        parent::__construct($argument);
        $this->annotationAliases = $annotationAliases;
        $this->annotations = Annotations::parseAll($this, $this->getDocComment(), $annotationAliases);
    }

    /**
     * Returns reflections of methods of this class as `ReflectionMethodAnnotated`
     *
     * @param ?int $filter Filters the methods to only include those with certain attributes
     * @param array $annotationAliases Annotation class aliases passed to `Annotations::parseAll()`.
     *  These aliases will be joined with the aliases used to construct this reflection.
     *
     * @return ReflectionPropertyAnnotated[]
     */
    public function getMethodsAnnotated(?int $filter = null, $annotationAliases = []): array {
        $methods = $filter !== null ? $this->getMethods($filter) : $this->getMethods();
        $all = [];
        foreach($methods as $method) {
            $all[] = new ReflectionMethodAnnotated(
                $this->getName(),
                $method->getName(),
                array_merge($this->annotationAliases, $annotationAliases));
        }
        return $all;
    }

    /**
     * Returns reflections of properies of this class as `ReflectionPropertyAnnotated`
     *
     * @param ?int $filter Filters the properties to only include those with certain attributes
     * @param array $annotationAliases Annotation class aliases passed to `Annotations::parseAll()`.
     *  These aliases will be joined with the aliases used to construct this reflection.
     *
     * @return ReflectionPropertyAnnotated[]
     */
    public function getPropertiesAnnotated(?int $filter = null, $annotationAliases = []): array {
        $properties = $filter !== null ? $this->getProperties($filter) : $this->getProperties();
        $all = [];
        foreach($properties as $property) {
            $all[] = new ReflectionPropertyAnnotated(
                $this->getName(),
                $property->getName(),
                array_merge($this->annotationAliases, $annotationAliases));
        }
        return $all;
    }

    /**
     * Extends the given reflection object into this class
     *
     * @param ReflectionClass $class The reflection object to convert
     * @param array $annotationAliases Annotation class aliases passed to `Annotations::parseAll()`
     */
    public static function from(ReflectionClass $class, $annotationAliases = []): ReflectionClassAnnotated {
        return new self($class->getName(), $annotationAliases);
    }

}
