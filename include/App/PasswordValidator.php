<?php

namespace App;

class PasswordValidator {

    /**
     * Tells if the given string can be safely used as a password
     */
    public static function isValidPassword(string $password): bool {
        if(strlen($password) < 5) return false;

        $hasDigit = false;
        $hasCapital = false;
        foreach(str_split($password) as $char) {
            if(ctype_digit($char)) {
                $hasDigit = true;
            }
            if(ctype_upper($char)) {
                $hasCapital = true;
            }
        }

        return $hasDigit && $hasCapital;
    }

}
