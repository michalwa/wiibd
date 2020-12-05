<?php

namespace App\Entities;

use Database\ORM\Entity;

/**
 * @Table('gatunki')
 */
class Genre extends Entity {

    /**
     * @Atomic('etykieta')
     * @var string
     */
    public $label;

    public function __toString(): string {
        return $this->label;
    }

}
