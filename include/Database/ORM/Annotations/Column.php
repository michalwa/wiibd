<?php

namespace Database\ORM\Annotations;

use App;
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
     * Foreign entity class name, if this column refers to another entity
     * @var null|string
     */
    private $foreignEntity = null;

    /**
     * {@inheritDoc}
     */
    public function __construct(Reflector $item, int $lineOffset, $params) {
        parent::__construct($item, $lineOffset);
        if( !($item instanceof ReflectionProperty) ) {
            throw new AnnotationException("@Column annotation can only be used on properties",
                $this->getItem(), $this->getLineOffset());
        }

        $this->name = count($params) >= 1 && is_string($params[0]) ? $params[0] : $item->getName();
        $this->propertyName = $item->getName();

        if(count($params) >= 2 && is_string($params[1])) {
            if(class_exists($params[1])) {
                $this->foreignEntity = $params[1];
            } else {
                $entitiesNamespace = App::get()->getConfig('database.entities');
                $this->foreignEntity = $entitiesNamespace.'\\'.$params[1];

                if(!class_exists($this->foreignEntity)) {
                    throw new AnnotationException("Entity class $params[1] could not be found",
                        $this->getItem(), $this->getLineOffset());
                }
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function allowMultiple(): bool {
        return false;
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
     * Returns the name of the foreign entity class, if this column references
     * another entity or `null` if not
     */
    public function getForeignEntityClassName(): ?string {
        return $this->foreignEntity;
    }

}
