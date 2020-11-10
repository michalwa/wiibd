<?php

namespace App\Entities;

use Database\ORM\Entity;
use Database\Query\Select;
use Utils\Stream;

/**
 * @Table('autorzy')
 */
class Author extends Entity {

    /**
     * @Atomic('imie')
     * @var string
     */
    public $firstName;

    /**
     * @Atomic('nazwisko')
     * @var string
     */
    public $lastName;

    public function __toString(): string {
        return "$this->lastName $this->firstName";
    }

    /**
     * Queries the repository for authors matching the given search query
     */
    public static function textSearch(string $search): Stream {
        return self::getRepository()->all(function(Select $q) use ($search) {
            foreach(explode(' ', $search) as $term) {
                if($term !== '') {
                    $q = $q
                        ->or('imie', 'LIKE', '%'.$term.'%')
                        ->or('nazwisko', 'LIKE', '%'.$term.'%');
                }
            }
            return $q;
        });
    }

}
