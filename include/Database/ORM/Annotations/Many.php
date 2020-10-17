<?php

namespace Database\ORM\Annotations;

use Database\ORM\ColumnSerde;
use Database\ORM\EntityClass;
use Database\ORM\MultipleForeignColumnSerde;
use Database\ORM\SingleForeignColumnSerde;
use \Reflector;
use \ReflectionProperty;
use Meta\Annotations\Annotation;
use Meta\Annotations\AnnotationException;

/**
 * `@Many` annotation to be used on entity class properties. Associates a
 * property with another entity type as a many-to-many relationship.
 *
 * Usage: `@Many(EntityClass, [crossTable], [leftForeignKey], [rightForeignKey])`
 *   - `EntityClass` - name of the associated entity class
 *   - `crossTable` - name of the table used to associate records of the two tables
 *                    (optional - defaults to `A__B`)
 *   - `leftForeignKey` - name of the column in the cross-table referring to records
 *                        in the table associated with the annotated entity
 *                        (optional - defaults to short entity class name)
 *   - `rightForeignKey` - name of the column in the cross-table referring to records
 *                         in the table associated with the foreign entity
 *                         (optional - defaults to short entity class name)
 */
class Many extends Annotation {

    /**
     * The name of the annotated property in the entity class
     * @var string
     */
    private $propertyName;

    /**
     * The name of the foreign entity class
     * @var string
     */
    private $foreignEntityClassName;

    /**
     * The name of the table used to associate records of the two tables
     * @var string
     */
    private $crossTableName;

    /**
     * The name of the column in the cross table referring to primary keys
     * of records in the table associated with the annotated entity
     * @var string
     */
    private $leftForeignKeyColumnName;

    /**
     * The name of the column in the cross table referring to primary keys
     * of records in the table associated with the foreign entity
     * @var string
     */
    private $rightForeignKeyColumnName;

    /**
     * {@inheritDoc}
     */
    public function __construct(Reflector $item, int $lineOffset, $params) {
        parent::__construct($item, $lineOffset);

        if( !($item instanceof ReflectionProperty) ) {
            throw new AnnotationException("@Many annotation can only be used on properties",
                $this->getItem(), $this->getLineOffset());
        }

        if(count($params) < 1) {
            throw new AnnotationException("@Many annotation requires 1 argument",
                $this->getItem(), $this->getLineOffset());
        }

        $className = $item->getDeclaringClass()->getShortName();

        $this->propertyName = $item->getName();
        $this->foreignEntityClassName = EntityClass::find($params[0]);

        $this->crossTableName = count($params) >= 2 ? $params[1]
            : $className.'__'.$this->foreignEntityClassName;

        $this->leftForeignKeyColumnName = count($params) >= 3 ? $params[2]
            : $className;

        $this->rightForeignKeyColumnName = count($params) >= 4 ? $params[3]
            : $this->foreignEntityClassName;
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
        return new MultipleForeignColumnSerde(
            $this->propertyName,
            $this->foreignEntityClassName,
            $this->crossTableName,
            $this->leftForeignKeyColumnName,
            $this->rightForeignKeyColumnName);
    }

}
