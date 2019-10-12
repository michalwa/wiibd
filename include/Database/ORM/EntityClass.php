<?php

namespace Database\ORM;

use \ReflectionClass;
use \InvalidArgumentException;
use Meta\Annotations\ReflectionClassAnnotated;
use Database\ORM\Annotations\Column;

/**
 * Reflects an entity class
 */
class EntityClass {

    /**
     * Annotation class aliases used for annotation parsing
     */
    private const ANNOTATION_ALIASES = [
        'Table'  => 'Database\ORM\Annotations\Table',
        'Column' => 'Database\ORM\Annotations\Column'
    ];

    /**
     * Instances of `EntityClass` for class names
     * @var EntityClass[string]
     */
    private static $classes = [];

    /**
     * The entity class reflection
     * @var ReflectionClass
     */
    private $class;

    /**
     * Column annotations
     * @var Column[]
     */
    private $columns = [];

    /**
     * Constructs a new `EntityClass` object
     */
    private function __construct(ReflectionClass $class) {
        if(!$class->isSubclassOf('Database\ORM\Entity')) {
            throw new InvalidArgumentException('Entity class must extend Database\ORM\Entity');
        }

        $this->class = $class;
        $properties = ReflectionClassAnnotated::from($class, self::ANNOTATION_ALIASES)->getPropertiesAnnotated();
        /** @var \Meta\Annotations\ReflectionPropertyAnnotated $property */
        foreach($properties as $property) {
            $annotation = $property->getAnnotation('Database\ORM\Annotations\Column');
            if($annotation !== null) $this->columns[] = $annotation;
        }
    }

    /**
     * Instantiates the entity class based on the given values.
     * 
     * @param array @values The column values
     * 
     * @return Entity|null The instantiated entity or `null` if `null` or `false` was passed
     */
    public function instantiate($values): ?Entity {
        if($values === null || $values === false) return null;
        $entity = $this->class->newInstance();
        foreach($this->columns as $column) {
            $property = $column->getPropertyName();
            $entity->$property = $values[$column->getName()];
        }
        return $entity;
    }

    /**
     * Returns an appropriate `EntityClass` for the given reflection class
     * 
     * @param ReflectionClass The entity class
     */
    public static function for(ReflectionClass $class): EntityClass {
        $name = $class->getName();
        if(!key_exists($name, self::$classes)) {
            self::$classes[$name] = new EntityClass($class);
        }
        return self::$classes[$name];
    }

}
