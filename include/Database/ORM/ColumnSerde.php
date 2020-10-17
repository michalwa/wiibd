<?php

namespace Database\ORM;

/**
 * Interfaces between properties in entity classes and actual columns in databases
 */
interface ColumnSerde {

    /**
     * Extracts the value for the column property from the given record
     * and injects it into the given entity
     *
     * @param array $record The record as an associative array
     * @param Entity $entity The entity to inject the value into
     */
    public function deserialize(array $record, Entity &$entity): void;

    /**
     * Extracts the value for this column from the given entity
     * and injects it into the given record associative array
     *
     * @param Entity $entity The entity
     * @param array $record The record to inject the column value into
     * @param Entity[] $refs An array of entities that are associated with the
     *                       given entity. Populate this with entities if they
     *                       need to be updated before the given entity.
     */
    public function serialize(Entity $entity, array &$record, array &$refs): void;

}
