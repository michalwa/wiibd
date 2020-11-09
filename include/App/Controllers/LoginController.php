<?php

namespace App\Controllers;

use App\Auth\UserSession;
use App\Entities\AdminUser;
use App\Entities\User;
use Controller\Controller;
use Http\Request;
use Http\Response;
use View\View;

class LoginController extends Controller {

    public const USER_NOT_FOUND = 'USER_NOT_FOUND';
    public const INCORRECT_PASS = 'INCORRECT_PASS';

    /**
     * @Route('GET', '/login')
     */
    public function form(Request $request, $params): ?Response {
        return View::load('login')->toResponse();
    }

    /**
     * @Route('POST', '/login/user')
     */
    public function userLogin(Request $request, $params): ?Response {
        $username = $request->getPost('username');
        $password = $request->getPost('password');

        if(!$username || !$password)
            return $this->redirectToSelf('form');

        $user = User::findByUsername($username);

        if($user === null)
            return View::load('login')->toResponse([
                'error' => self::USER_NOT_FOUND,
            ]);

        if(!$user->passwordEquals($password))
            return View::load('login')->toResponse([
                'error' => self::INCORRECT_PASS,
            ]);

        UserSession::loginUser($user);
        return $this->redirect(IndexController::class.'::index');
    }

    /**
     * @Route('POST', '/login/admin')
     */
    public function adminLogin(Request $request, $params): ?Response {
        $username = $request->getPost('username');
        $password = $request->getPost('password');

        if(!$username || !$password)
            return $this->redirectToSelf('form');

        $user = AdminUser::findByUsername($username);

        if($user === null)
            return View::load('login')->toResponse([
                'error' => self::USER_NOT_FOUND,
            ]);

        if(!$user->passwordEquals($password))
            return View::load('login')->toResponse([
                'error' => self::INCORRECT_PASS,
            ]);

        UserSession::loginAdmin($user);
        return $this->redirect(IndexController::class.'::index');
    }

    /**
     * @Route('GET', '/logout')
     */
    public function logout(Request $request, $params): ?Response {
        UserSession::logout();
        return $this->redirect(IndexController::class.'::index');
    }

}
