<?php

namespace Database\ORM\Annotations;

use \Reflector;
use \ReflectionProperty;
use \InvalidArgumentException;
use Meta\Annotation;

class Column extends Annotation {

    /**
     * The column name
     * @var string
     */
    private $name;

    /**
     * The property name
     * @var string
     */
    private $propertyName;

    /**
     * Constructs a new `Column` annotation object
     */
    private function __construct(string $name, string $propertyName) {
        $this->name = $name;
        $this->propertyName = $propertyName;
    }

    /**
     * Returns the column name
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Returns the property name
     */
    public function getPropertyName(): string {
        return $this->propertyName;
    }

    /**
     * {@inheritDoc}
     */
    public static function instantiate(Reflector $item, ?object $object, $params): Annotation {
        if( !($item instanceof ReflectionProperty) ) {
            throw new InvalidArgumentException("@Column can only be used on properties");
        }
        $itemName = $item->getName();
        $name = count($params) > 0 && is_string($params[0]) ? $params[0] : $itemName;
        return new Column($name, $itemName);
    }

}
