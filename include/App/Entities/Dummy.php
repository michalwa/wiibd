<?php

namespace App\Entities;

use Database\ORM\Entity;

/**
 * This associates this entity type with the table 'Dummy'
 * instead of the default which would be just 'Dummy'
 *
 * @Table()
 */
class Dummy extends Entity {

    /**
     * This associates this property with the column 'name'
     *
     * @Atomic()
     * @var string
     */
    public $name;

}
