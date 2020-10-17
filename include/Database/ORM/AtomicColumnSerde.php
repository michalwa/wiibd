<?php

namespace Database\ORM;

/**
 * Atomic column (de)serializer
 */
class AtomicColumnSerde implements ColumnSerde {

    /**
     * The column property name in the entity class
     */
    private $propertyName;

    /**
     * The database column name
     */
    private $columnName;

    /**
     * Constructs a new atomic column resolver
     *
     * @param string $propertyName The column property name in the entity class
     * @param string $columnName The database column name
     */
    public function __construct(string $propertyName, string $columnName) {
        $this->propertyName = $propertyName;
        $this->columnName = $columnName;
    }

    /**
     * {@inheritDoc}
     */
    public function deserialize(array $record, Entity &$entity): void {
        if(!key_exists($this->columnName, $record)) {
            throw new ColumnSerdeException("Value for column {$this->columnName} missing");
        }

        $prop = $this->propertyName;
        $entity->$prop = $record[$this->columnName];
    }

    /**
     * {@inheritDoc}
     */
    public function serialize(Entity $entity, array &$record, array &$refs): void {
        if(!property_exists(get_class($entity), $this->propertyName)) {
            throw new ColumnSerdeException("Column property {$this->propertyName} does not exist");
        }

        $prop = $this->propertyName;
        $record[$this->columnName] = $entity->$prop;
    }

}
