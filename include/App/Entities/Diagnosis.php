<?php

namespace App\Entities;

use Database\ORM\Entity;
use Database\Query\Select;

/**
 * @Table('diagnoses')
 */
class Diagnosis extends Entity {

    /**
     * @One('Appointment', 'appointment')
     * @var Appointment
     */
    public $appointment;

    /**
     * @Atomic('body')
     * @var string
     */
    public $body;

    /**
     * @Atomic('icd10')
     * @var string|null
     */
    public $icd10;

    public static function findByAppointmentId(int $id)
    {
        return self::getRepository()->all(fn(Select $f) => $f
            ->where('appointment', '=', $id));
    }

}
