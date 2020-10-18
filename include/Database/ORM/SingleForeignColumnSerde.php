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
    public function serialize(EntityProxy $entity, array &$record, array &$refs): void {
        $foreign = $entity->getProperty($this->propertyName);
        $id = $record[$this->columnName]
            = $foreign === null ? null : $foreign->getId();

        if($foreign) {
            $refs[] = Repository
                ::for($this->foreignEntityClassName)
                ->findById($id);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function deserialize(array $record, EntityProxy $entity): void {
        if(!key_exists($this->columnName, $record)) {
            throw new ColumnSerdeException("Value for column {$this->columnName} missing");
        }

        $foreign = Repository
            ::for($this->foreignEntityClassName)
            ->findById($record[$this->columnName]);

        $entity->setProperty($this->propertyName, $foreign);
        $foreign->addRef($entity->getEntity());
    }

    /**
     * {@inheritDoc}
     */
    public function unref(EntityProxy $referrer, Entity $referee): array {
        if($referrer->getProperty($this->propertyName) === $referee) {
            $referrer->setProperty($this->propertyName, null);
        }
        return [$referrer->getEntity()];
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
