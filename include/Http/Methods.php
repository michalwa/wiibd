<?php

namespace Http;

/**
 * HTTP Methods
 */
class Methods {

    public const METHODS = [
        'GET',
        'POST',
        'PUT',
        'DELETE',
        'PATCH',
        'HEAD',
        'OPTIONS',
        'CONNECT',
        'TRACE'
    ];

    /**
     * Returns a regular expression matching any HTTP method mnemonic
     */
    public static function getRegex(): string {
        return implode('|', self::METHODS);
    }

}
