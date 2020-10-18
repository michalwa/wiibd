<?php

namespace Database\ORM;

/**
 * Entity classes define models for database entries
 */
abstract class Entity {

    /**
     * The required primary key column.
     * Non-nullity indicates existence in the database.
     *
     * @Atomic()
     * @var int
     */
    protected $id; // Has to be protected to be seen by reflections of subclasses

    /**
     * References to this entity
     * @var Entity[]
     */
    private $refs = [];

    /**
     * Registers a reference to this entity held by another entity
     *
     * @param Entity $referrer The referring entity
     *
     * @return self $this
     */
    public function addRef(self $referrer): self {
        $this->refs[] = $referrer;
        return $this;
    }

    /**
     * Deletes references to this entity held by other entities
     *
     * @return Entity[] Array of entities that need to be updated
     */
    public function deleteRefs(): array {
        $affected = [];
        foreach($this->refs as $ref) {
            array_append($affected, $ref->unref($this));
        }
        $this->refs = [];
        return $affected;
    }

    /**
     * Removes the reference to the given entity from this entity
     *
     * @return Entity[] Array of entities that need to be updated
     */
    public function unref(Entity $entity): array {
        return EntityClass::for(get_called_class())
            ->unref($this, $entity);
    }

    /**
     * Returns the primary key of this entity
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * Sets the primary key of this entity
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * Sets the primary key of this entity to `null`, disassociating this entity
     * from its record in the database
     */
    public function unlink(): void {
        $this->id = null;
    }

    /**
     * Returns `true`, if this entity has a valid record in the database
     */
    public function hasRecord(): bool {
        return $this->id !== null;
    }

    /**
     * Shorthand for calling `Repository::persist(Entity)` on this entity
     */
    public function persist(): void {
        Repository::for(get_called_class())->persist($this);
    }

    /**
     * Shorthand for calling `Repository::delete(Entity)` on this entity
     */
    public function delete(): void {
        Repository::for(get_called_class())->delete($this);
    }

    public function __toString(): string {
        return '<'.get_called_class().':'.$this->id.'>';
    }

    /**
     * Returns the repository for this entity type
     */
    public static function getRepository(): Repository {
        return Repository::for(get_called_class());
    }

}
