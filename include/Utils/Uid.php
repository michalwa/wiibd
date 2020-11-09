<?php

namespace Utils;

/**
 * Generates runtime-unique IDs
 */
class Uid {

    /**
     * @var int
     */
    private static $next = 1;

    /**
     * Returns a new runtime-unique ID and increments it
     */
    public static function next(): string {
        return hash('md5', self::$next++);
    }

}
