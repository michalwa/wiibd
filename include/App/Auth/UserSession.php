<?php

namespace App\Auth;

use App\Entities\AdminUser;
use App\Entities\User;
use Utils\Session;

/**
 * Manages logged in users
 */
class UserSession {

    /**
     * Logs in the given user
     */
    public static function loginUser(User $user): void {
        Session::unset('admin');
        Session::set('user', $user->getId());
    }

    /**
     * Logs in the given admin user
     */
    public static function loginAdmin(AdminUser $admin): void {
        Session::unset('user');
        Session::set('admin', $admin->getId());
    }

    /**
     * Logs out any logged in user
     */
    public static function logout(): void {
        Session::unset('user');
        Session::unset('admin');
    }

    /**
     * Returns the currently logged in user
     */
    public static function getUser(): ?User {
        if(($id = Session::get('user')) !== null) {
            return User::getRepository()->findById($id);
        }
        return null;
    }

    /**
     * Returns the currently logged in admin user
     */
    public static function getAdmin(): ?AdminUser {
        if(($id = Session::get('admin')) !== null) {
            return AdminUser::getRepository()->findById($id);
        }
        return null;
    }

    /**
     * Tells whether an admin user is currently logged in
     */
    public static function isAdmin(): bool {
        return Session::isset('admin');
    }

    /**
     * Tells whether a user is currently logged in.
     * Additionally checks if the user has the specified ID, if provided.
     */
    public static function isUser(?int $id = null): bool {
        if($id !== null) {
            return Session::get('user') === $id;
        }
        return Session::isset('user');
    }

}
