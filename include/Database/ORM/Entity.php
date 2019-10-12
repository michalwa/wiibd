<?php

namespace Database\ORM;

use \ReflectionClass;
use Database\ORM\Annotations\Table;
use Meta\Annotations;

/**
 * Entity classes define models for database entries
 */
class Entity {

    /**
     * Returns the repository for this entity type
     */
    public static function getRepository(): Repository {
        $class = new ReflectionClass(get_called_class());
        $annotations = Annotations::parseAll($class, null, $class->getDocComment(), [
            'Table' => 'Database\ORM\Annotations\Table'
        ]);
        $tableName = str_replace('\\', '_', $class->getName());
        foreach($annotations as $annotation) {
            if($annotation instanceof Table) {
                $tableName = $annotation->getName();
                break;
            }
        }
        return Repository::for($class->getName(), $tableName);
    }

    /**
     * Deserializes the given database result row
     * into an entity object of the specified class.
     * 
     * @param mixed[string] $values The row fetched from the database
     * @param ReflectionClass $entityClass The entity class to instantiate
     * 
     * @return Entity|null The deserialized entity or `null` if `null` was passed
     */
    public static function deserialize($values, ReflectionClass $entityClass): ?Entity {
        return EntityClass::for($entityClass)->instantiate($values);
    }

}
