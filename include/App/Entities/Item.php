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

    public function available(): bool {
        return Borrow::findActiveByItemId($this->getId()) === null;
    }

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

        // NOTE: Nie wiem jak to zrobić inaczej na razie

        $items = self::getRepository()->all(fn($q) => $q
            ->where('ksiazka', '=', $id))
            ->toArray();

        return Stream::begin(array_filter($items, fn($i) => $i->available()));
    }

    /**
     * Queries the database for an item with the specified identifier
     */
    public static function findByIdentifier(string $id): ?self {
        return self::getRepository()->find(fn(Select $q) => $q
            ->where('identyfikator', '=', $id));
    }
}
