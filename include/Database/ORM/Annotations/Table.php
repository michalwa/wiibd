<?php

namespace Database\ORM\Annotations;

use InvalidArgumentException;
use \Reflector;
use Meta\Annotation;

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
     * Constructs a new `Table` annotation object
     * 
     * @param string $name The table name
     */
    private function __construct(string $name) {
        $this->name = $name;
    }

    /**
     * Returns the table name
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public static function instantiate(Reflector $item, ?object $object, $params): Annotation {
        if(count($params) != 1) {
            throw new InvalidArgumentException("Invalid number of parameters.");
        }
        return new Table($params[0].'');
    }

}
