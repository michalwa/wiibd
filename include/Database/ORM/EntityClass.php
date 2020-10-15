<?php

namespace Database\ORM;

use \ReflectionClass;
use \InvalidArgumentException;
use Meta\Annotations\ReflectionClassAnnotated;
use Database\ORM\Annotations\Column;
use Database\ORM\Annotations\Table;

/**
 * Reflects an entity class
 */
class EntityClass {

    /**
     * Annotation class aliases used for annotation parsing
     */
    private const ANNOTATION_ALIASES = [
        'Table'  => Table::class,
        'Column' => Column::class,
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
     * The table name for the entity type
     * @var string
     */
    private $tableName;

    /**
     * Column annotations
     * @var Column[]
     */
    private $columns = [];

    /**
     * Constructs a new `EntityClass` object
     */
    private function __construct(string $className) {
        $this->class = new ReflectionClassAnnotated($className, self::ANNOTATION_ALIASES);

        if(!$this->class->isSubclassOf(Entity::class)) {
            throw new InvalidArgumentException("Entity class must extend ".Entity::class);
        }

        $this->tableName = str_replace('\\', '_', $className);

        /** @var null|Table */
        $tableAnno = $this->class->getAnnotation(Table::class);
        if($tableAnno !== null) $this->tableName = $tableAnno->getName();

        $properties = $this->class->getPropertiesAnnotated();

        /** @var \Meta\Annotations\ReflectionPropertyAnnotated $property */
        foreach($properties as $property) {
            $annotation = $property->getAnnotation(Column::class);
            if($annotation !== null) $this->columns[] = $annotation;
        }
    }

    /**
     * Returns the table name configured for this entity class
     */
    public function getTableName(): string {
        return $this->tableName;
    }

    /**
     * Instantiates the entity class based on the given values.
     *
     * @param array @values The column values
     *
     * @return null|Entity The instantiated entity or `null` if `null` or `false` was passed
     */
    public function deserialize($values): ?Entity {
        if($values === null || $values === false) return null;
        $entity = $this->class->newInstance();
        foreach($this->columns as $column) {
            $property = $column->getPropertyName();
            $entity->$property = $values[$column->getName()];
        }
        return $entity;
    }

    /**
     * Serializes the given entity into a record as an associative array
     *
     * @param Entity $entity The entity to serialize
     * @param bool $includeId Whether to include the primary key column
     */
    public function serialize(Entity $entity, bool $includeId = false): array {
        $record = [];
        foreach($this->columns as $column) {
            $prop = $column->getPropertyName();
            $record[$column->getName()] = $entity->$prop;
        }

        if(!$includeId) unset($record['id']);

        return $record;
    }

    /**
     * Returns an appropriate `EntityClass` for the given entity class name
     *
     * @param string The entity class name
     */
    public static function for(string $className): EntityClass {
        if(!key_exists($className, self::$classes)) {
            self::$classes[$className] = new EntityClass($className);
        }
        return self::$classes[$className];
    }

}
