<?php

namespace App\Entities;

use Database\ORM\Entity;

/**
 * @Table('doctors')
 */
class Doctor extends Entity {

    /**
     * @Atomic('title')
     * @var string
     */
    public $title;

    /**
     * @Atomic('speciality')
     * @var string
     */
    public $speciality;

}
