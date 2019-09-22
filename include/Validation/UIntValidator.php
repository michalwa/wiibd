<?php

namespace Validation;

/**
 * Validates unsigned integer strings
 */
class UIntValidator extends Validator {

    public function isValid(string $value): bool {
        $value = str_replace(' ', '', trim($value));
        return preg_match('/^([0-9])+$/', $value);
    }

}
