<?php

namespace Database\ORM;

use \ReflectionClass;
use Meta\Annotations\ReflectionClassAnnotated;

/**
 * Entity classes define models for database entries
 */
class Entity {

    /**
     * Annotation class aliases used in annotation parsing
     */
    private const ANNOTATION_ALIASES = [
        'Table' => 'Database\ORM\Annotations\Table'
    ];

    /**
     * Returns the repository for this entity type
     */
    public static function getRepository(): Repository {
        $class = new ReflectionClassAnnotated(get_called_class(), self::ANNOTATION_ALIASES);
        $tableName = str_replace('\\', '_', $class->getName());
        /** @var \Database\ORM\Annotations\Table|null $annotation */
        $annotation = $class->getAnnotation('Database\ORM\Annotations\Table');
        if($annotation !== null) $tableName = $annotation->getName();
        
        return Repository::for($class->getName(), $tableName);
    }

    /**
     * Deserializes the given database result row
     * into an entity object of the specified class.
     * 
     * @param array $values The row fetched from the database
     * @param ReflectionClass $entityClass The entity class to instantiate
     * 
     * @return Entity|null The deserialized entity or `null` if `null` or `false` was passed
     */
    public static function deserialize($values, ReflectionClass $entityClass): ?Entity {
        return EntityClass::for($entityClass)->instantiate($values);
    }

}
