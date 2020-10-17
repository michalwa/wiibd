<?php

namespace Database\ORM;

/**
 * *-to-one foreign entity column (de)serializer
 */
class SingleForeignColumnSerde implements ColumnSerde {

    /**
     * The name of the annotated property in the entity class
     */
    private $propertyName;

    /**
     * The name of the column holding the foreign keys
     */
    private $columnName;

    /**
     * The name of the foreign entity class
     */
    private $foreignEntityClassName;

    /**
     * Constructs a new *-to-one column (de)serializer
     *
     * @param string $propertyName The name of the annotated property in the entity class
     * @param string $columnName The name of the column holding the foreign keys
     * @param string $foreignEntityClassName The name of the foreign entity class
     */
    public function __construct(
        string $propertyName,
        string $columnName,
        string $foreignEntityClassName
    ) {
        $this->propertyName = $propertyName;
        $this->columnName = $columnName;
        $this->foreignEntityClassName = $foreignEntityClassName;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize(Entity $entity, array &$record, array &$refs): void {
        if(!property_exists(get_class($entity), $this->propertyName)) {
            throw new ColumnSerdeException("Column property {$this->propertyName} does not exist");
        }

        $prop = $this->propertyName;
        $record[$this->columnName] = $entity->$prop->id;

        $refs[] = Repository
            ::for($this->foreignEntityClassName)
            ->findById($entity->$prop->id);
    }

    /**
     * {@inheritDoc}
     */
    public function deserialize(array $record, Entity &$entity): void {
        if(!key_exists($this->columnName, $record)) {
            throw new ColumnSerdeException("Value for column {$this->columnName} missing");
        }

        $prop = $this->propertyName;
        $entity->$prop = Repository
            ::for($this->foreignEntityClassName)
            ->findById($record[$this->columnName]);
    }

}
