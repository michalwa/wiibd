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
     * Queries the repository for books matching the given search query
     */
    public static function textSearch(string $search): Stream {
        return self::getRepository()->all(function(Select $q) use ($search) {
            foreach(explode(' ', $search) as $term) {
                if($term !== '') {
                    $q = $q->where('tytul', 'LIKE', '%'.$term.'%');
                }
            }
            return $q;
        });
    }

    /**
     * Queries the repository for books published by the specified author
     */
    public static function findByAuthorId(int $id): Stream {
        return self::getRepository()->query(fn($params) => <<<SQL
            SELECT ksiazki.*
            FROM ksiazki
            INNER JOIN ksiazki_autorzy ON ksiazki_autorzy.ksiazka = ksiazki.id
            INNER JOIN autorzy ON autorzy.id = ksiazki_autorzy.autor
            WHERE autorzy.id = {$params->add($id)};
        SQL);
    }

}
