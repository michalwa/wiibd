<?php

namespace App\Entities;

use Database\ORM\Entity;

/**
 * @Table('egzemplarze')
 */
class Item extends Entity {

    /**
     * @Atomic('identyfikator')
     * @var string
     */
    public $identifier;

    /**
     * @One('Book', 'ksiazka')
     * @var Book
     */
    public $book;

    /**
     * @Atomic('dostepny')
     * @var bool
     */
    public $available;

}
