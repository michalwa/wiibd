<?php

namespace Database\ORM;

/**
 * Interfaces between properties in entity classes and actual columns in databases
 */
interface ColumnSerde {

    /**
     * Extracts the value for this column from the given entity
     * and injects it into the given record associative array
     *
     * @param EntityProxy $entity The entity to serialize
     * @param array $record The record to inject the column value into
     * @param Entity[] $refs An array of entities that are referenced by this entity
     *                 and need to be updated in case this entity needs to be updated.
     */
    public function serialize(EntityProxy $entity, array &$record, array &$refs): void;

    /**
     * Extracts the value for the column property from the given record
     * and injects it into the given entity
     *
     * @param array $record The record as an associative array
     * @param EntityProxy $entity The entity to inject the value into
     */
    public function deserialize(array $record, EntityProxy $entity): void;

    /**
     * Removes all references to the referee from the referrer
     *
     * @param Entity $referrer The entity holding a reference to the other entity
     * @param Entity $referee The entity to which the reference is held
     *
     * @return Entity[] Array of entities that need to be updated
     */
    public function unref(EntityProxy $referrer, Entity $referee): array;

    /**
     * Called before the entity is persisted to the database
     *
     * @param EntityProxy $entity The entity to be persisted
     */
    public function beforePersist(EntityProxy $entity): void;

    /**
     * Called after the entity is persisted to the database
     *
     * @param EntityProxy $entity The persisted entity
     */
    public function afterPersist(EntityProxy $entity): void;

}
