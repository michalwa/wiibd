<?php

namespace Database\ORM\Annotations;

use \Reflector;
use \ReflectionProperty;
use Meta\Annotations\Annotation;
use Meta\Annotations\AnnotationException;

/**
 * @Column annotation to be used on entity class properties
 * Usage: `@Column([name])`
 */
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
    public function __construct(Reflector $item, $params) {
        parent::__construct($item);
        if( !($item instanceof ReflectionProperty) ) {
            throw new AnnotationException("@Column annotation can only be used on properties");
        }

        $this->name = count($params) > 0 && is_string($params[0]) ? $params[0] : $item->getName();
        $this->propertyName = $item->getName();
    }

    /**
     * {@inheritDoc}
     */
    public static function single(): bool {
        return true;
    }

}
