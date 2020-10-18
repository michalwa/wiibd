<?php

namespace App\Entities;

use Database\ORM\Entity;

/**
 * @Table('wydawnictwa')
 */
class Publisher extends Entity {

    /**
     * @Atomic('nazwa')
     * @var string
     */
    public $name;

    public function __toString(): string {
        return $this->name;
    }

}
