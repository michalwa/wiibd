<?php

namespace App\Entities;

use Database\ORM\Entity;

/**
 * This associates this entity type with the table 'Dummy'
 * instead of the default which would be 'App_Entities_Dummy'
 * (the name argument is optional but parentheses are required)
 * 
 * @Table()
 */
class Dummy extends Entity {

    /**
     * This associates this property with the column 'name'
     * 
     * @Column()
     * @var string
     */
    public $name;

}
