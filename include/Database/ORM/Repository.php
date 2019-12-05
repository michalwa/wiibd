<?php

namespace Database\ORM;

use \ReflectionClass;
use Database\Database;
use Database\DatabaseException;
use Utils\Stream;

/**
 * Allows retrieving and persisting entities from and to the database
 */
class Repository {

    /**
     * Existing repositories
     */
    private static $repositories = [];

    /**
     * The name of the associated table
     * @var string
     */
    protected $tableName;

    /**
     * The entity class
     * @var ReflectionClass
     */
    protected $entityClass;

    /**
     * Constructs a new repository for the given entity class
     * 
     * @param ReflectionClass $entityClass The entity class
     */
    private function __construct(ReflectionClass $entityClass, string $tableName) {
        $this->entityClass = $entityClass;
        $this->tableName = $tableName;
    }

    /**
     * Queries the database for an entity with the given id and returns the result
     * 
     * @param int $id The id of the entity to find in the database
     */
    public function findById(int $id): ?Entity {
        $result = Database
            ::select()
            ->from($this->tableName)
            ->where('id', '=', $id)
            ->execute();

        if(!$result->ok()) {
            throw new DatabaseException("Query failed: ".$result->getQueryString());
        }

        return Entity::deserialize($result->get(), $this->entityClass);
    }

    /**
     * Queries the database for all entities of the appropriate type and returns the result
     * 
     * @return Iterator[Entity]
     */
    public function all(): iterable {
        $result = Database
            ::select()
            ->from($this->tableName)
            ->execute();

        if(!$result->ok()) {
            throw new DatabaseException("Query failed: ".$result->getQueryString());
        }

        return Stream::begin($result)
            ->map(function($row) { return Entity::deserialize($row, $this->entityClass); });
    }

    /**
     * Returns (creates, if it doesn't exist) a repository for the specified class
     * 
     * @param string $className The full name of the entity class
     */
    public static function for(string $className, string $tableName): Repository {
        if(!key_exists($className, self::$repositories)) {
            self::$repositories[$className] = new Repository(new ReflectionClass($className), $tableName);
        }
        return self::$repositories[$className];
    }

}
