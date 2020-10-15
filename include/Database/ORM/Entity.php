<?php

namespace Database\ORM;

/**
 * Entity classes define models for database entries
 */
class Entity {

    /**
     * The required primary key column.
     * Non-nullity indicates existence in the database.
     *
     * @Column('id')
     * @var int
     */
    public $id;

    /**
     * Shorthand for calling `Repository::persist(Entity)` on this entity
     *
     * @throws DatabaseException If the operation fails
     */
    public function persist(): void {
        Repository::for(get_called_class())->persist($this);
    }

    /**
     * Returns the repository for this entity type
     */
    public static function getRepository(): Repository {
        return Repository::for(get_called_class());
    }

}
