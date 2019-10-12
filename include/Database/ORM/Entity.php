<?php

namespace Database\ORM;

use \ReflectionClass;

/**
 * Entity classes define models for database entries
 */
class Entity {

    /**
     * Returns the repository for this entity type
     */
    public static function getRepository(): Repository {
        return Repository::for(get_called_class());
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
