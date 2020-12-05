<?php

namespace App\Entities;

use Database\ORM\Entity;

/**
 * @Table('appointments')
 */
class Appointment extends Entity {

    /**
     * @One('Doctor', 'doctor')
     * @var Doctor
     */
    public $doctor;

    /**
     * @One('Patient', 'patient')
     * @var Patient
     */
    public $patient;

    /**
     * @Atomic('starts')
     * @var string
     */
    public $starts;

    /**
     * @Atomic('ends')
     * @var string
     */
    public $ends;

    /**
     * @Atomic('description')
     * @var string
     */
    public $description;

}
