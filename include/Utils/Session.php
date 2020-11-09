<?php

namespace Utils;

use RuntimeException;

/**
 * Manages the session storage
 */
class Session {

    /**
     * Sets the specified property in session storage to the given value
     */
    public static function set(string $key, $value) {
        self::checkSessions();
        $_SESSION[$key] = $value;
    }

    /**
     * Removes the specified property from session storage
     */
    public static function unset(string $key) {
        self::checkSessions();
        unset($_SESSION[$key]);
    }

    /**
     * Returns the value of the specified property in session storage
     * or the specified default
     */
    public static function get(string $key, $default = null) {
        self::checkSessions();
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    /**
     * Tells whether the specified property exists in the session storage
     */
    public static function isset(string $key): bool {
        return isset($_SESSION[$key]);
    }

    private static function checkSessions() {
        if(session_status() === PHP_SESSION_DISABLED)
            throw new RuntimeException("Sessions are disabled.");

        if(session_status() === PHP_SESSION_NONE)
            session_start();
    }

}
