<?php

namespace Database\ORM\Annotations;

use Database\ORM\ColumnSerde;
use Database\ORM\EntityClass;
use Database\ORM\SingleForeignColumnSerde;
use \Reflector;
use \ReflectionProperty;
use Meta\Annotations\Annotation;
use Meta\Annotations\AnnotationException;

/**
 * `@One` annotation to be used on entity class properties. Associates a
 * property with another entity type as a many-to-one relationship.
 *
 * Usage: `@One(EntityClass, [columnName])`
 *   - `EntityClass` - name of the associated entity class
 *   - `columnName` - name of the associated database column
 *                    (optional - defaults to property name)
 */
class One extends Annotation {

    /**
     * The property name
     * @var string
     */
    private $propertyName;

    /**
     * The name of the foreign entity class
     * @var string
     */
    private $foreignEntityClassName;

    /**
     * The column name
     * @var string
     */
    private $columnName;

    /**
     * {@inheritDoc}
     */
    public function __construct(Reflector $item, int $lineOffset, $params) {
        parent::__construct($item, $lineOffset);

        if( !($item instanceof ReflectionProperty) ) {
            throw new AnnotationException("@One annotation can only be used on properties",
                $this->getItem(), $this->getLineOffset());
        }

        if(count($params) < 1) {
            throw new AnnotationException("@One annotation requires 1 argument",
                $this->getItem(), $this->getLineOffset());
        }

        $this->propertyName = $item->getName();
        $this->foreignEntityClassName = EntityClass::find($params[0]);
        $this->columnName = count($params) >= 1 ? $params[1] : $this->propertyName;
    }

    /**
     * {@inheritDoc}
     */
    public static function allowMultiple(): bool {
        return false;
    }

    /**
     * Returns the (de)serializer for the annotated property
     */
    public function getSerde(): ColumnSerde {
        return new SingleForeignColumnSerde(
            $this->propertyName,
            $this->columnName,
            $this->foreignEntityClassName);
    }

}
