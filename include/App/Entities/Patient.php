<?php

namespace App\Entities;

use Database\ORM\Entity;

/**
 * @Table('patients')
 */
class Patient extends Entity {

    /**
     * @Atomic('pesel')
     * @var string
     */
    public $pesel;

    /**
     * @Atomic('birth')
     * @var string
     */
    public $birth;

    /**
     * @Atomic('address')
     * @var string
     */
    public $address;

    /**
     * @Atomic('city')
     * @var string
     */
    public $city;

    /**
     * @Atomic('postal_code')
     * @var string
     */
    public $postalCode;

}
