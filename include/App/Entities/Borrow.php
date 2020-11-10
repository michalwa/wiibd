<?php

namespace App\Entities;

use Database\ORM\Entity;
use Database\Query\QueryParams;
use Database\Query\Select;
use Utils\Stream;

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

    /**
     * Finds borrows associated with the user with the specified ID
     * and an item that are not available
     *
     * @param int $id The user id to use in the query
     *
     * @return Stream<Borrow>
     */
    public static function findActiveByUserId(int $id): Stream {
        return self::getRepository()->all(fn(Select $q) => $q
            ->join('INNER', 'egzemplarze', 'egzemplarz')
            ->where('wypozyczenia.czytelnik', '=', $id)
            ->and('egzemplarze.dostepny', '=', 0));
    }

}
