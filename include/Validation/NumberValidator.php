<?php

namespace Validation;

/**
 * Validates any number - signed/unsigned integer or float
 */
class NumberValidator extends Validator {

    public function isValid(string $value): bool {
        return preg_match('/^(-?(\d*\.)?\d+)$/', $value);
    }

}
