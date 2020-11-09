<?php

namespace App\Controllers;

use App;
use App\Auth\UserSession;
use App\Entities\AdminUser;
use App\Entities\User;
use Content\Form\Form;
use Content\Form\PasswordField;
use Content\Form\TextField;
use Controller\Controller;
use Http\Request;
use Http\Response;
use View\View;

class LoginController extends Controller {

    public const USER_NOT_FOUND = 'USER_NOT_FOUND';
    public const INCORRECT_PASS = 'INCORRECT_PASS';

    /**
     * @var Form
     */
    private $userForm;

    /**
     * @var Form
     */
    private $adminForm;

    public function __construct() {
        parent::__construct();

        $this->userForm = (new Form('POST', App::routeUrl(self::class, 'userLogin')))
            ->addField(new TextField('username', ['label' => 'Login']))
            ->addField(new PasswordField('password', ['label' => 'Hasło']));

        $this->adminForm = (new Form('POST', App::routeUrl(self::class, 'adminLogin')))
            ->addField(new TextField('username', ['label' => 'Login']))
            ->addField(new PasswordField('password', ['label' => 'Hasło']));
    }

    /**
     * @Route('GET', '/login')
     */
    public function form(Request $request, $params): ?Response {
        return View::load('login')->toResponse([
            'userForm' => $this->userForm,
            'adminForm' => $this->adminForm,
        ]);
    }

    /**
     * @Route('POST', '/login/user')
     */
    public function userLogin(Request $request, $params): ?Response {
        $username = $this->userForm->getValue('username');
        $password = $this->userForm->getValue('password');

        if(!$username || !$password)
            return $this->redirectToSelf('form');

        $user = User::findByUsername($username);

        if($user === null)
            return View::load('login')->toResponse([
                'userForm' => $this->userForm,
                'adminForm' => $this->adminForm,
                'error' => self::USER_NOT_FOUND,
            ]);

        if(!$user->passwordEquals($password))
            return View::load('login')->toResponse([
                'userForm' => $this->userForm,
                'adminForm' => $this->adminForm,
                'error' => self::INCORRECT_PASS,
            ]);

        UserSession::loginUser($user);
        return $this->redirect(IndexController::class.'::index');
    }

    /**
     * @Route('POST', '/login/admin')
     */
    public function adminLogin(Request $request, $params): ?Response {
        $username = $this->adminForm->getValue('username');
        $password = $this->adminForm->getValue('password');

        if(!$username || !$password)
            return $this->redirectToSelf('form');

        $user = AdminUser::findByUsername($username);

        if($user === null)
            return View::load('login')->toResponse([
                'userForm' => $this->userForm,
                'adminForm' => $this->adminForm,
                'error' => self::USER_NOT_FOUND,
            ]);

        if(!$user->passwordEquals($password))
            return View::load('login')->toResponse([
                'userForm' => $this->userForm,
                'adminForm' => $this->adminForm,
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
