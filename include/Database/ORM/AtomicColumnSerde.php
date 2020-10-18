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
    public function serialize(EntityProxy $entity, array &$record, array &$refs): void {
        $record[$this->columnName] = $entity->getProperty($this->propertyName);
    }

    /**
     * {@inheritDoc}
     */
    public function deserialize(array $record, EntityProxy $entity): void {
        if(!key_exists($this->columnName, $record)) {
            throw new ColumnSerdeException("Value for column {$this->columnName} missing");
        }

        $entity->setProperty($this->propertyName, $record[$this->columnName]);
    }

    /**
     * {@inheritDoc}
     */
    public function unref(EntityProxy $referrer, Entity $referee): array {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function beforePersist(EntityProxy $entity): void {}

    /**
     * {@inheritDoc}
     */
    public function afterPersist(EntityProxy $entity): void {}

}
