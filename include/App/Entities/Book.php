<?php

namespace App\Entities;

use Database\ORM\Entity;

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

}
