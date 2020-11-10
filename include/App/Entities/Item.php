<?php

namespace App\Entities;

use Database\ORM\Entity;
use Utils\Stream;

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

    /**
     * Fetches available copies of the specified book from the repository
     */
    public static function findAvailableByBookId(int $id): Stream {
        return self::getRepository()->all(fn($q) => $q
            ->where('ksiazka', '=', $id)
            ->and('dostepny', '=', 1));
    }

}
