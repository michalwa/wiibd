<?php

namespace App\Entities;

use Database\ORM\Entity;
use Database\Query\Select;
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
     * Queries the repository for books matching the given search query
     */
    public static function textSearch(string $search): Stream {
        return self::getRepository()->all(function(Select $q) use ($search) {
            foreach(explode(' ', $search) as $term) {
                if($term !== '') {
                    $q = $q
                        ->join('INNER', 'ksiazki', 'ksiazka')
                        ->where('identyfikator', 'LIKE', '%'.$term.'%')
                        ->or('ksiazki.tytul', 'LIKE', '%'.$term.'%');
                }
            }
            return $q;
        });
    }

    /**
     * Fetches available copies of the specified book from the repository
     */
    public static function findAvailableByBookId(int $id): Stream {
        return self::getRepository()->all(fn($q) => $q
            ->where('ksiazka', '=', $id)
            ->and('dostepny', '=', 1));
    }
}
