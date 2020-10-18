<?php

namespace Database\ORM;

use ReflectionClass;

/**
 * Exposes an interface for accessing an entity's properties
 */
class EntityProxy {

    /**
     * The proxied entity
     */
    private $entity;

    /**
     * Reflection of the class of the proxied entity
     * @var ReflectionClass
     */
    private $class;

    /**
     * Constructs a new proxy for the given entity and its class
     *
     * @param Entity $entity The entity to proxy
     * @param null|ReflectionClass $class The class of the entity
     */
    public function __construct(Entity $entity, ?ReflectionClass $class = null) {
        $this->entity = $entity;
        $this->class = $class ?? new ReflectionClass(get_class($entity));
    }

    /**
     * Returns the proxied entity
     */
    public function getEntity(): Entity {
        return $this->entity;
    }

    /**
     * Sets the specified property of the entity to the given value
     *
     * @param string $name The name of the property to set
     * @param mixed $value The value to assign to the property
     *
     * @throws \ReflectionException If the property does not exist
     */
    public function setProperty(string $name, $value): void {
        $prop = $this->class->getProperty($name);

        if(!($public = $prop->isPublic())) $prop->setAccessible(true);

        $prop->setValue($this->entity, $value);

        if(!$public) $prop->setAccessible(false);
    }

    /**
     * Returns the value of the specified property
     *
     * @param string $name The name of the property to get
     *
     * @return mixed The value of the property
     *
     * @throws \ReflectionException If the property does not exist
     */
    public function getProperty(string $name) {
        $prop = $this->class->getProperty($name);

        if(!($public = $prop->isPublic())) $prop->setAccessible(true);

        $value = $prop->getValue($this->entity);

        if(!$public) $prop->setAccessible(false);

        return $value;
    }

}
