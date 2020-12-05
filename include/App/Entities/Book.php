<?php

namespace App\Entities;

use Database\ORM\Entity;
use Database\Query\Select;
use Utils\Stream;

/**
 * @Table('ksiazki')
 */
class Book extends Entity {

    /**
     * @Atomic('tytul')
     * @var string
     */
    public $title;

    /**
     * @Atomic('rokWydania')
     * @var string
     */
    public $releaseYear;

    /**
     * @One('Publisher', 'wydawnictwo')
     * @var Publisher
     */
    public $publisher;

    /**
     * @Many('Author', 'ksiazki_autorzy', 'ksiazka', 'autor')
     * @var Author[]
     */
    public $authors;

    /**
     * @Many('Genre', 'ksiazki_gatunki', 'ksiazka', 'gatunek')
     * @var Genre[]
     */
    public $genres;

    /**
     * Fetches the number of available copies of this book from the database
     */
    public function numAvailableCopies(): int {
        return count(Item::findAvailableByBookId($this->getId())->toArray());
    }

    /**
     * Queries the repository for books matching the given search query
     */
    public static function search(?string $search = null, ?array $genres = []): Stream {
        return self::getRepository()->all(function(Select $q) use ($search, $genres) {
            if($search !== null) {
                foreach(explode(' ', $search) as $term) {
                    if($term !== '') {
                        /** @var Select $q */
                        $q = $q->where('tytul', 'LIKE', '%'.$term.'%');
                    }
                }
            }

            if($genres !== null) $q = $q
                ->join('INNER', 'ksiazki_gatunki', 'id', 'ksiazka')
                ->where('gatunek', 'IN', $genres);

            return $q;
        });
    }

    /**
     * Queries the repository for books published by the specified author
     */
    public static function findByAuthorId(int $id): Stream {
        return self::getRepository()->all(fn(Select $q) => $q
            ->join('INNER', 'ksiazki_autorzy', 'id', 'ksiazka')
            ->join('INNER', 'autorzy', 'autor', 'id', 'ksiazki_autorzy')
            ->where('autorzy.id', '=', $id));
    }

}
