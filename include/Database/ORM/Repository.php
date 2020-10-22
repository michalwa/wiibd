<?php

namespace Database\ORM;

use Database\Database;
use Database\DatabaseException;
use InvalidArgumentException;
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

        if(!$result->ok() || !($row = $result->get())) return null;

        $entity = $this->entityClass->deserialize($row);
        $this->cached[$entity->getId()] = $entity;
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
            ->execute()
            ->expect();

        $entities = Stream::begin($result)

            // Deserialize
            ->map(fn($row) => $this->entityClass->deserialize($row))

            // Substitute new entities with existing cached entities
            ->map(fn($en) => key_exists($en->getId(), $this->cached) ? $this->cached[$en->getId()] : $en)

            // Cache each consumed entity
            ->map(function($en) { $this->cached[$en->getid()] = $en; return $en; });

        return $entities;
    }

    /**
     * Persists the given entity to the database by either inserting it (if it doesn't exist)
     * or updating its record (if it exists).
     *
     * @param Entity $entity The entity to persist
     * @param bool $safe If `true`, the entire operation will be done in a transaction
     *             and rolled back if something fails
     *
     * @throws DatabaseException If the operation fails
     */
    public function persist(Entity $entity, bool $safe = true): void {
        if($safe) {
            Database::get()->beginTransaction();

            try {
                $this->persist($entity, false);
            } catch(\Exception $e) {
                Database::get()->rollBack();
                throw $e;
            }

            Database::get()->commit();
            return;
        }

        $cls = $this->entityClass->getReflection();
        if(!$cls->isInstance($entity)) {
            throw new InvalidArgumentException(
                "Entity is not associated with this repository (must be an instance of {$cls->getName()})");
        }

        $this->entityClass->beforePersist($entity);

        $refs = [];
        $record = $this->entityClass->serialize($entity, $refs);

        foreach($refs as $ref) {
            $ref->persist(false);
        }

        if(!$entity->hasRecord()) {
            Database
                ::insert($record)
                ->into($this->entityClass->getTableName())
                ->execute()
                ->expect();

            $id = Database::get()
                ->query('SELECT @@IDENTITY')
                ->expect()
                ->get()['@@IDENTITY'];

            $entity->setId($id);
            $this->cached[$id] = $entity;
        } else {
            Database
                ::update($this->entityClass->getTableName())
                ->setAll($record)
                ->except('id')
                ->where('id', '=', $entity->getId())
                ->execute()
                ->expect();
        }

        $this->entityClass->afterPersist($entity);
    }

    /**
     * Deletes the entity from the database and from the repository
     *
     * @param Entity $entity The entity to delete
     * @param bool $safe If `true`, the entire operation will be done in a transaction
     *             and rolled back if something fails
     *
     * @throws DatabaseException If the operation fails
     */
    public function delete(Entity $entity, bool $safe = true): void {
        if($safe) {
            Database::get()->beginTransaction();

            try {
                $this->delete($entity, false);
            } catch(\Exception $e) {
                Database::get()->rollBack();
                throw $e;
            }

            Database::get()->commit();
            return;
        }

        $cls = $this->entityClass->getReflection();
        if(!$cls->isInstance($entity)) {
            throw new InvalidArgumentException(
                "Entity is not associated with this repository (must be an instance of {$cls->getName()})");
        }

        foreach($entity->deleteRefs() as $affected) {
            if($affected->hasRecord()) {
                $affected->persist(false);
            }
        }

        Database::delete()
            ->from($this->entityClass->getTableName())
            ->where('id', '=', $entity->getId())
            ->execute()
            ->expect();

        unset($this->cached[$entity->getId()]);
        $entity->unlink();
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
