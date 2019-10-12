<?php

namespace App\Entities;

use Database\ORM\Entity;

/**
 * @Table('Dummy')
 */
class Dummy extends Entity {

    /**
     * @Column()
     * @var string
     */
    public $name;

}
