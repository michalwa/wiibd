<?php

namespace Database\ORM;

use App;
use \ReflectionClass;
use \InvalidArgumentException;
use Meta\Annotations\ReflectionClassAnnotated;
use Database\ORM\Annotations\Table;
use Database\ORM\Annotations\Atomic;
use Database\ORM\Annotations\One;
use Database\ORM\Annotations\Many;

/**
 * Reflects an entity class
 */
class EntityClass {

    /**
     * Annotation class aliases used for annotation parsing
     */
    private const ANNOTATION_ALIASES = [
        'Table' => Table::class,
        'Atomic' => Atomic::class,
        'One' => One::class,
        'Many' => Many::class,
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
     * Column (de)serializers
     * @var ColumnSerde[]
     */
    private $serde = [];

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

        $props = $this->class->getPropertiesAnnotated();
        foreach($props as $prop) {
            if($anno = $prop->getAnnotation(Atomic::class)) $this->serde[] = $anno->getSerde();
            elseif($anno = $prop->getAnnotation(One::class)) $this->serde[] = $anno->getSerde();
            elseif($anno = $prop->getAnnotation(Many::class)) $this->serde[] = $anno->getSerde();
        }
    }

    /**
     * Returns the table name configured for this entity class
     */
    public function getTableName(): string {
        return $this->tableName;
    }

    /**
     * Serializes the given entity into a record as an associative array
     *
     * @param Entity $entity The entity to serialize
     * @param Entity[] $refs Array to be populated with entities that need to be updated
     *                       before this entity
     * @param bool $includeId Whether to include the primary key column
     *
     * @return mixed[string]
     */
    public function serialize(Entity $entity, array &$refs = [], bool $includeId = false): array {
        if(!$this->class->isInstance($entity)) {
            throw new InvalidArgumentException("Entity is not an instance of the reflected class");
        }

        $proxy = new EntityProxy($entity, $this->class);
        $record = [];

        foreach($this->serde as $serde) {
            $serde->serialize($proxy, $record, $refs);
        }

        if(!$includeId) unset($record['id']);

        return $record;
    }

    /**
     * Instantiates the entity class based on the given values.
     *
     * @param mixed[string] @values The column values
     *
     * @return null|Entity The instantiated entity or `null` if `null` or `false` was passed
     */
    public function deserialize(array $values): ?Entity {
        $entity = $this->class->newInstance();
        $proxy = new EntityProxy($entity, $this->class);

        foreach($this->serde as $serde) {
            $serde->deserialize($values, $proxy);
        }

        return $entity;
    }

    /**
     * Removes all references to the referee from the referrer
     *
     * @param Entity $referrer The entity holding a reference to the other entity
     * @param Entity $referee The entity to which the reference is held
     *
     * @param Entity[] Array of entities that need to be updated
     */
    public function unref(Entity $referrer, Entity $referee): array {
        if(!$this->class->isInstance($referrer)) {
            throw new InvalidArgumentException("Entity is not an instance of the reflected class");
        }

        $proxy = new EntityProxy($referrer, $this->class);

        $affected = [];
        foreach($this->serde as $serde) {
            array_append($affected, $serde->unref($proxy, $referee));
        }
        return $affected;
    }

    /**
     * Called before the entity is persisted to the database
     *
     * @param Entity $entity The entity to be persisted
     */
    public function beforePersist(Entity $entity): void {
        if(!$this->class->isInstance($entity)) {
            throw new InvalidArgumentException("Entity is not an instance of the reflected class");
        }

        $proxy = new EntityProxy($entity, $this->class);
        foreach($this->serde as $serde) {
            $serde->beforePersist($proxy);
        }
    }

    /**
     * Called after the entity is persisted to the database
     *
     * @param Entity $entity The persisted entity
     */
    public function afterPersist(Entity $entity): void {
        if(!$this->class->isInstance($entity)) {
            throw new InvalidArgumentException("Entity is not an instance of the reflected class");
        }

        $proxy = new EntityProxy($entity, $this->class);
        foreach($this->serde as $serde) {
            $serde->afterPersist($proxy);
        }
    }

    /**
     * Returns the reflection of the entity class
     */
    public function getReflection(): ReflectionClass {
        return $this->class;
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

    /**
     * Finds the entity class with the given name. Can be full name or short name,
     * in which case the configured entities namespace will be searched for the class.
     *
     * @param string $name The entity class name to find
     *
     * @return string The resolved full class name
     */
    public static function find(string $name): string {
        if(class_exists($name)) {
            return $name;
        } else {
            $entitiesNamespace = App::getConfig('database.entities');
            $name = $entitiesNamespace.'\\'.$name;

            if(!class_exists($name)) {
                throw new InvalidArgumentException("Entity class $name could not be found");
            }

            return $name;
        }
    }

}
