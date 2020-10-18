<?php

namespace Database\ORM;

use Database\Database;
use Utils\Stream;

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
    public function serialize(EntityProxy $entity, array &$record, array &$refs): void {
        array_append($refs, $entity->getProperty($this->propertyName));
    }

    /**
     * {@inheritDoc}
     */
    public function deserialize(array $record, EntityProxy $entity): void {
        $entities = [];

        foreach($this->fetchForeignIds($record['id']) as $id) {
            $foreign = $entities[] = Repository
                ::for($this->foreignEntityClassName)
                ->findById($id);

            $foreign->addRef($entity->getEntity());
        }

        $entity->setProperty($this->propertyName, $entities);
    }

    /**
     * {@inheritDoc}
     */
    public function unref(EntityProxy $referrer, Entity $referee): array {
        $entities = $referrer->getProperty($this->propertyName);
        array_remove($entities, $referee);
        $referrer->setProperty($this->propertyName, $entities);

        return [$referrer->getEntity()];
    }

    /**
     * {@inheritDoc}
     */
    public function beforePersist(EntityProxy $entity): void {
        if(!$entity->getEntity()->hasRecord()) return;

        $dbIds = $this->fetchForeignIds($entity->getEntity()->getId())
            ->toArray();

        $actualIds = Stream
            ::begin($entity->getProperty($this->propertyName))
            ->map(fn($entity) => $entity->getId())
            ->toArray();

        Database::delete()
            ->from($this->crossTableName)
            ->where($this->leftForeignKeyColumnName, '=', $entity->getEntity()->getId())
            ->and($this->rightForeignKeyColumnName, 'IN', array_diff($dbIds, $actualIds))
            ->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function afterPersist(EntityProxy $entity): void {
        $dbIds = $this->fetchForeignIds($entity->getEntity()->getId())
            ->toArray();

        foreach($entity->getProperty($this->propertyName) as $foreign) {
            if(!$foreign->hasRecord()) {
                $foreign->persist(false);
            }
        }

        $actualIds = Stream
            ::begin($entity->getProperty($this->propertyName))
            ->map(fn($entity) => $entity->getId())
            ->toArray();

        foreach(array_diff($actualIds, $dbIds) as $addedId) {
            Database
                ::insert([
                    $this->leftForeignKeyColumnName => $entity->getEntity()->getId(),
                    $this->rightForeignKeyColumnName => $addedId])
                ->into($this->crossTableName)
                ->execute()
                ->expect();
        }
    }

    /**
     * Fetches foreign entity IDs from the database based on the entity ID
     *
     * @param int $id The primary key of the entity of the annotated class
     *
     * @return Stream<int> The fetched IDs
     */
    private function fetchForeignIds(int $id): Stream {
        $foreignTableName = EntityClass
            ::for($this->foreignEntityClassName)
            ->getTableName();

        $result = Database
            ::select("$foreignTableName.id")
            ->from($foreignTableName)
            ->join('INNER', $this->crossTableName, $this->rightForeignKeyColumnName)
            ->where($this->leftForeignKeyColumnName, '=', $id)
            ->execute()
            ->expect();

        return Stream::begin($result)
            ->map(fn($row) => $row['id']);
    }

}
