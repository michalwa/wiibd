<?php

namespace Database\ORM\Annotations;

use \Reflector;
use Meta\Annotations\Annotation;
use Meta\Annotations\AnnotationException;
use ReflectionClass;

/**
 * @Table annotation to be used on entity classes
 * Usage: `@Table(name)`
 */
class Table extends Annotation {

    /**
     * The table name
     * @var string
     */
    private $name;

    /**
     * {@inheritDoc}
     */
    public function __construct(Reflector $item, int $lineOffset, $params) {
        parent::__construct($item, $lineOffset);
        if( !($item instanceof ReflectionClass) ) {
            throw new AnnotationException("@Table annotation can only be used on classes");
        }
        $this->name = count($params) > 0 && is_string($params[0]) ? $params[0] : $item->getShortName();
    }

    /**
     * {@inheritDoc}
     */
    public static function allowMultiple(): bool {
        return false;
    }

    /**
     * Returns the table name
     */
    public function getName(): string {
        return $this->name;
    }

}
