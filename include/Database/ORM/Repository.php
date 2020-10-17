<?php

namespace Database\ORM;

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
     * The entity class
     * @var EntityClass
     */
    protected $entityClass;

    /**
     * Cached entities (id => entity)
     * @var Entity[]
     */
    private $cached = [];

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
        if(key_exists($id, $this->cached)) {
            return $this->cached[$id];
        }

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

        $entity = $this->entityClass->deserialize($result->get());
        $this->cached[$entity->id] = $entity;
        return $entity;
    }

    /**
     * Queries the database for all entities of the appropriate type and returns the result
     *
     * @return Stream<Entity>
     */
    public function all(): Stream {
        $result = Database
            ::select()
            ->from($this->entityClass->getTableName())
            ->execute();

        if(!$result->ok()) {
            throw new DatabaseException("Query failed:"
                .' '.$result->getQueryInfoHtml()
                .' ('.$result->getErrorInfo().')');
        }

        $entities = Stream::begin($result)

            // Deserialize
            ->map(fn($row) => $this->entityClass->deserialize($row))

            // Substitute new entities with existing cached entities
            ->map(fn($en) => key_exists($en->id, $this->cached) ? $this->cached[$en->id] : $en)

            // Cache each consumed entity
            ->map(function($en) { $this->cached[$en->id] = $en; return $en; });

        return $entities;
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

        $refs = [];
        $record = $this->entityClass->serialize($entity, $refs);

        foreach($refs as $ref) {
            $ref->persist();
        }

        if($entity->id === null) {
            $result = Database
                ::insert($record)
                ->into($this->entityClass->getTableName())
                ->execute();
        } else {
            $result = Database
                ::update($this->entityClass->getTableName())
                ->setAll($record)
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
