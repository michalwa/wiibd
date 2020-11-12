<?php

namespace App\Controllers;

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

        $this->userForm = (new Form('POST'))
            ->addField(new TextField('username', true, ['label' => 'Login']))
            ->addField(new PasswordField('password', true, ['label' => 'Hasło']));

        $this->adminForm = (new Form('POST'))
            ->addField(new TextField('username', true, ['label' => 'Login']))
            ->addField(new PasswordField('password', true, ['label' => 'Hasło']));
    }

    /**
     * @Route('GET', '/login')
     */
    public function form(Request $request, $params): ?Response {
        $this->userForm->setAction(self::routeUrl('userLogin', [], $request->getQuery()));
        $this->adminForm->setAction(self::routeUrl('adminLogin', [], $request->getQuery()));

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

        if(!$this->userForm->isValid())
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

        if(($url = $request->getQuery('redirect')) !== null)
            return Response::redirect($url);

        return Response::redirect(IndexController::routeUrl('index'));
    }

    /**
     * @Route('POST', '/login/admin')
     */
    public function adminLogin(Request $request, $params): ?Response {
        $username = $this->adminForm->getValue('username');
        $password = $this->adminForm->getValue('password');

        if(!$this->adminForm->isValid())
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

        if(($url = $request->getQuery('redirect')) !== null)
            return Response::redirect($url);

        return Response::redirect(IndexController::routeUrl('index'));
    }

    /**
     * @Route('GET', '/logout')
     */
    public function logout(Request $request, $params): ?Response {
        UserSession::logout();

        if(($url = $request->getQuery('redirect')) !== null)
            return Response::redirect($url);

        return Response::redirect(IndexController::routeUrl('index'));
    }

}
