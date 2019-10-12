<?php

namespace Database\ORM;

use \ReflectionClass;
use \InvalidArgumentException;
use Meta\Annotations;
use Database\ORM\Annotations\Column;

/**
 * Reflects an entity class
 */
class EntityClass {

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
     * Columns
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
        foreach($this->class->getProperties() as $property) {
            $annotations = Annotations::parseAll($property, null, $property->getDocComment(), [
                'Column' => 'Database\ORM\Annotations\Column'
            ]);

            foreach($annotations as $annotation) {
                if($annotation instanceof Column) {
                    $this->columns[] = $annotation;
                }
            }
        }
    }

    /**
     * Instantiates the entity class based on the given values.
     * 
     * @param mixed[string] @values The column values
     * 
     * @return Entity|null The instantiated entity or `null` if `null` was passed
     */
    public function instantiate($values): ?Entity {
        if($values === null) return null;
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
