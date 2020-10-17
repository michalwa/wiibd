<?php

namespace Database\ORM\Annotations;

use Database\ORM\AtomicColumnSerde;
use Database\ORM\ColumnSerde;
use Database\ORM\EntityClass;
use \Reflector;
use \ReflectionProperty;
use Meta\Annotations\Annotation;
use Meta\Annotations\AnnotationException;

/**
 * `@Atomic` annotation to be used on entity class properties. Associates a
 * property with a database table column.
 *
 * Usage: `@Atomic([name])`
 *   - `name` - name of the associated database column (optional - defaults to
 *              property name)
 */
class Atomic extends Annotation {

    /**
     * The column name
     * @var string
     */
    private $columnName;

    /**
     * The property name
     * @var string
     */
    private $propertyName;

    /**
     * {@inheritDoc}
     */
    public function __construct(Reflector $item, int $lineOffset, $params) {
        parent::__construct($item, $lineOffset);

        if( !($item instanceof ReflectionProperty) ) {
            throw new AnnotationException("@Column annotation can only be used on properties",
                $this->getItem(), $this->getLineOffset());
        }

        $this->columnName = count($params) >= 1 ? $params[0] : $item->getName();
        $this->propertyName = $item->getName();
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
        return new AtomicColumnSerde($this->propertyName, $this->columnName);
    }

}
