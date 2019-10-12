<?php

namespace Database\ORM\Annotations;

use \Reflector;
use Meta\Annotations\Annotation;
use Meta\Annotations\AnnotationException;

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
     * Returns the table name
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function __construct(Reflector $item, $params) {
        parent::__construct($item);
        if(count($params) != 1) {
            throw new AnnotationException("Invalid number of parameters.");
        }
        $this->name = (string)$params[0];
    }

}
