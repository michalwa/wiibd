<?php

namespace Database\ORM;

use \ReflectionClass;
use Database\Database;
use Database\DatabaseException;
use Database\Result;
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
     * The entity class
     * @var EntityClass
     */
    protected $entityClass;

    /**
     * Constructs a new repository for the given entity class
     *
     * @param EntityClass $entityClass The entity class
     */
    private function __construct(EntityClass $entityClass) {
        $this->entityClass = $entityClass;
    }

    /**
     * Queries the database for an entity with the given id and returns the result
     *
     * @param int $id The id of the entity to find in the database
     */
    public function findById(int $id): ?Entity {
        $result = Database
            ::select()
            ->from($this->entityClass->getTableName())
            ->where('id', '=', $id)
            ->execute();

        if(!$result->ok()) {
            throw new DatabaseException("Query failed:"
                .' '.$result->getQueryInfoHtml()
                .' '.$result->getErrorInfo());
        }

        return $this->entityClass->deserialize($result->get());
    }

    /**
     * Queries the database for all entities of the appropriate type and returns the result
     *
     * @return Iterator[Entity]
     */
    public function all(): iterable {
        $result = Database
            ::select()
            ->from($this->entityClass->getTableName())
            ->execute();

        if(!$result->ok()) {
            throw new DatabaseException("Query failed:"
                .' '.$result->getQueryInfoHtml()
                .' ('.$result->getErrorInfo().')');
        }

        return Stream::begin($result)
            ->map(fn($row) => $this->entityClass->deserialize($row));
    }

    /**
     * Persists the given entity to the database by either inserting it (if it doesn't exist)
     * or updating its record (if it exists).
     *
     * @param Entity $entity The entity to persist
     *
     * @throws DatabaseException If the operation fails
     */
    public function persist(Entity $entity): void {

        // Find foreign entities and persist them first
        foreach($this->entityClass->getForeignEntities($entity) as $foreign) {
            $foreign->persist();
        }

        if($entity->id === null) {
            $result = Database
                ::insert($this->entityClass->serialize($entity))
                ->into($this->entityClass->getTableName())
                ->execute();
        } else {
            $result = Database
                ::update($this->entityClass->getTableName())
                ->setAll($this->entityClass->serialize($entity))
                ->except('id')
                ->where('id', '=', $entity->id)
                ->execute();
        }

        if(!$result->ok()) {
            throw new DatabaseException("Query failed:"
                .' '.$result->getQueryInfoHtml()
                .' ('.$result->getErrorInfo().')');
        }
    }

    /**
     * Returns (creates, if it doesn't exist) a repository for the specified class
     *
     * @param string $className The full name of the entity class
     */
    public static function for(string $className): Repository {
        if(!key_exists($className, self::$repositories)) {
            self::$repositories[$className] = new Repository(EntityClass::for($className));
        }
        return self::$repositories[$className];
    }

}
