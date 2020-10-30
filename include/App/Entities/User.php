<?php

namespace App\Entities;

use Database\ORM\Entity;
use Database\Query\Select;

/**
 * @Table('czytelnicy')
 */
class User extends Entity {

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

    /**
     * @Atomic('klasa')
     * @var string
     */
    public $class;

    /**
     * @Atomic('aktywny')
     * @var bool
     */
    public $active;

    /**
     * @Atomic('login')
     * @var string
     */
    public $username;

    /**
     * @Atomic('haslo')
     * @var string
     */
    private $password;

    public function __toString(): string {
        return "$this->firstName $this->lastName";
    }

    /**
     * Sets the password for this user
     */
    public function setPassword(string $password): self {
        $this->password = hash('sha512', $password);
        return $this;
    }

    /**
     * Compares the given password against this user's password
     */
    public function passwordEquals(string $password): bool {
        return hash('sha512', $password) === $this->password;
    }

    /**
     * Queries the repository for a user with the specified username
     */
    public static function findByUsername(string $username): ?self {
        return self::getRepository()->find(fn(Select $q) => $q
            ->where('login', '=', $username));
    }

}