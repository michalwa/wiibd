<?php

namespace Validation;

/**
 * Validates strings
 */
abstract class Validator {

    /**
     * Globally registered validators
     * @var Validator[]
     */
    private static $validators = [];

    /**
     * Validates the given string and returns whether it is valid.
     * 
     * @param string $value The string to validate
     */
    public abstract function isValid(string $value): bool;

    /**
     * Globally registers the given validator under the given name.
     * 
     * @param string $name The name of the validator
     * @param Validator $validator The validator to register
     */
    public static function register(string $name, Validator $validator) {
        if(array_key_exists($name, self::$validators)) {
            die('Validator "'.$name.'" already existst.');
        }
        self::$validators[$name] = $validator;
    }

    /**
     * Validates the given string using the validator with the specified name.
     * 
     * @param string $value The string to validate
     * @param string $validator The name of the validator to use
     */
    public static function validate(string $value, string $validator): bool {
        if(array_key_exists($validator, self::$validators)) {
            return self::$validators[$validator]->isValid($value);
        }
        throw new ValidationException('No validator under the name "'.$validator.'" found');
    }

}

// Register default validators
Validator::register('uint',   new UIntValidator());
Validator::register('number', new NumberValidator());
