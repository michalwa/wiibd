<?php

namespace App\Entities;

use Database\ORM\Entity;

/**
 * @Table('wypozyczenia')
 */
class Borrow extends Entity {

    /**
     * @One('Item', 'egzemplarz')
     * @var Item
     */
    public $item;

    /**
     * @One('User', 'czytelnik')
     * @var User
     */
    public $user;

    /**
     * @Atomic('dataRozpoczecia')
     * @var string
     */
    public $began;

    /**
     * @Atomic('dataZakonczenia')
     * @var string
     */
    public $ends;

}
