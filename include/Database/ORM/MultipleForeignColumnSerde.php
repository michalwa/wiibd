<?php

namespace Database\ORM;

use Database\Database;

/**
 * many-to-many foreign entity column (de)serializer
 */
class MultipleForeignColumnSerde implements ColumnSerde {

    /**
     * The name of the annotated property in the entity class
     * @var string
     */
    private $propertyName;

    /**
     * The name of the foreign entity class
     * @var string
     */
    private $foreignEntityClassName;

    /**
     * The name of the table used to associate records of the two tables
     * @var string
     */
    private $crossTableName;

    /**
     * The name of the column in the cross table referring to primary keys
     * of records in the table associated with the annotated entity
     * @var string
     */
    private $leftForeignKeyColumnName;

    /**
     * The name of the column in the cross table referring to primary keys
     * of records in the table associated with the foreign entity
     * @var string
     */
    private $rightForeignKeyColumnName;

    /**
     * Constructs a new many-to-many column (de)serializer
     *
     * @param string $propertyName The name of the annotated property in the entity class
     * @param string $foreignEntityClass The name of the foreign entity class
     * @param string $crossTableName The name of the table used to associate
     *               records of the two tables
     * @param string $leftForeignKeyColumnName The name of the column in the cross
     *               table referring to primary keys  of records in the table associated
     *               with the annotated entity
     * @param string $rightForeignKeyColumnName The name of the column in the cross
     *               table referring to primary keys of records in the table associated
     *               with the foreign entity
     */
    public function __construct(
        string $propertyName,
        string $foreignEntityClassName,
        string $crossTableName,
        string $leftForeignKeyColumnName,
        string $rightForeignKeyColumnName
    ) {
        $this->propertyName = $propertyName;
        $this->foreignEntityClassName = $foreignEntityClassName;
        $this->crossTableName = $crossTableName;
        $this->leftForeignKeyColumnName = $leftForeignKeyColumnName;
        $this->rightForeignKeyColumnName = $rightForeignKeyColumnName;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize(Entity $entity, array &$record, array &$refs): void {
        $cls = get_class($entity);

        if(!property_exists($cls, $this->propertyName)) {
            throw new ColumnSerdeException(
                "Column property $cls::{$this->propertyName} does not exist");
        }

        $prop = $this->propertyName;
        $refs = array_merge($refs, $entity->$prop);
    }

    /**
     * {@inheritDoc}
     */
    public function deserialize(array $record, Entity &$entity): void {
        $foreignTableName = EntityClass
            ::for($this->foreignEntityClassName)
            ->getTableName();

        $result = Database
            ::select(["$foreignTableName.id"])
            ->join('INNER', $this->crossTableName, $this->rightForeignKeyColumnName)
            ->from($foreignTableName)
            ->where($this->leftForeignKeyColumnName, '=', $record['id'])
            ->execute();

        if(!$result->ok()) {
            throw new ColumnSerdeException("Could not fetch foreign entity IDs");
        }

        $prop = $this->propertyName;
        $entity->$prop = [];

        foreach(iterator_to_array($result) as $id) {
            $entity->$prop[] = Repository
                ::for($this->foreignEntityClassName)
                ->findById($id['id']);
        }
    }

}
