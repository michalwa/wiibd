<?php

namespace App\Entities;

use Database\ORM\Entity;

/**
 * @Table('users')
 */
class User extends Entity {

    public const ROLE_RECEPTIONIST = 1;
    public const ROLE_DOCTOR = 2;
    public const ROLE_PATIENT = 3;

    /**
     * @Atomic('username')
     * @var string
     */
    public $username;

    /**
     * @Atomic('password')
     * @var string
     */
    private $passwordHash;

    /**
     * @Atomic('first_name')
     * @var string
     */
    public $firstName;

    /**
     * @Atomic('last_name')
     * @var string
     */
    public $lastName;

    /**
     * @Atomic('role')
     * @var int
     */
    public $role;

    /**
     * @One('Doctor', 'doctor')
     * @var Doctor|null
     */
    public $doctor = null;

    /**
     * @One('Patient', 'patient')
     * @var Doctor|null
     */
    public $patient = null;

    public function setPassword(string $password)
    {
        $this->passwordHash = hash('sha512', $password);
    }

    public function passwordEquals(string $password)
    {
        return $this->passwordHash == hash('sha512', $password);
    }

}
