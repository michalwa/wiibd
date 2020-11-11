<?php

namespace App\Entities;

use Database\ORM\Entity;
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
     * @Atomic('aktywne')
     * @var bool
     */
    public $active;

    /**
     * Finds active borrows associated with the user with the specified ID
     *
     * @param int $id The user id to use in the query
     *
     * @return Stream<Borrow>
     */
    public static function findActiveByUserId(int $id): Stream {
        return self::getRepository()->all(fn(Select $q) => $q
            ->where('czytelnik', '=', $id)
            ->and('aktywne', '=', 1));
    }

    /**
     * Finds an active borrow associated with the item with the specified ID
     *
     * @param int $id The item id to use in the query
     *
     * @return null|Borrow
     */
    public static function findActiveByItemId(int $id): ?self {
        return self::getRepository()->find(fn(Select $q) => $q
            ->join('INNER', 'egzemplarze', 'egzemplarz')
            ->where('egzemplarze.id', '=', $id)
            ->and('aktywne', '=', 1));
    }

    /**
     * Finds borrows associated with the specified book
     *
     * @return Stream<Borrow>
     */
    public static function findByBookId(int $id): Stream {
        return self::getRepository()->all(fn(Select $q) => $q
            ->join('INNER', 'egzemplarze', 'egzemplarz')
            ->where('egzemplarze.ksiazka', '=', $id));
    }

}
