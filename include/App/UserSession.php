<?php

namespace App;

use App\Entities\User;
use Utils\Session;

/**
 * Manages logging users in/out
 */
class UserSession {

    public const USERNAME_DOES_NOT_EXIST = 1;
    public const INVALID_PASSWORD = 2;

    /**
     * Tries to log in with the given credentials and returns one of:
     *  - `0` (success)
     *  - `USERNAME_DOES_NOT_EXIST`
     *  - `INVALID_PASSWORD`
     */
    public static function login(string $username, string $password): int {
        if(Session::isset('user')) return 0;

        /** @var null|User */
        if($user = User::findByUsername($username)) {
            if($user->passwordEquals($password)) {
                Session::set('user', $user->getId());
                return 0;
            }

            return self::INVALID_PASSWORD;
        }

        return self::USERNAME_DOES_NOT_EXIST;
    }

    /**
     * Logs out the currently logged in user
     */
    public static function logout() {
        Session::unset('user');
    }

    /**
     * Returns the currently logged in user or `null`
     */
    public static function user(): ?User {
        return ($id = Session::isset('user'))
            ? User::getRepository()->findById($id)
            : null;
    }

}
