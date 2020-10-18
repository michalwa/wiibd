<?php

namespace App\Entities;

use Database\ORM\Entity;

/**
 * @Table('autorzy')
 */
class Author extends Entity {

    /**
     * @Atomic('imie')
     * @var string
     */
    public $firstName;

    /**
     * @Atomic('nazwisko')
     * @var string
     */
    public $lastName;

    public function __toString(): string {
        return "$this->firstName $this->lastName";
    }

}
